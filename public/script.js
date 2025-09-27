document.addEventListener("DOMContentLoaded", () => {
    // -- Konfigurasi & State Global -------------------------------------------
    const API_BASE_URL = "/api";
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    let conversations = [];
    let activeConversation = null;
    let pagination = {
        currentPage: 1,
        lastPage: 1,
        isLoading: false,
    };

    // -- Elemen-elemen DOM ----------------------------------------------------
    const $ = (sel) => document.querySelector(sel);
    const historyList = $("#historyList");
    const messagesWrap = $("#messages");
    const messageInput = $("#messageInput");
    const sendBtn = $("#sendBtn");
    const newChatBtn = $("#newChatBtn");
    const statusTyping = $("#statusTyping");
    const chatContainer = messagesWrap.parentElement;
    const clearBtnTop = $("#clearBtnTop");
    const clearHistoryBtn = $("#clearHistory");

    // -- API Helpers -----------------------------------------------------------
    async function deleteConversation(conversationId) {
        return await apiFetch(`/conversations/${conversationId}`, {
            method: "DELETE",
        });
    }

    async function clearAllConversations() {
        return await apiFetch("/conversations", {
            method: "DELETE",
        });
    }
    async function apiFetch(endpoint, options = {}) {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            ...options,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken,
                ...options.headers,
            },
        });
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || "API request failed");
        }
        return response.json();
    }

    // -- Fungsi Logika Utama ---------------------------------------------------

    async function fetchConversations() {
        const response = await apiFetch("/conversations");
        conversations = response.data.data;
        renderHistory();
        if (conversations.length > 0 && !activeConversation) {
            await selectChat(conversations[0].id);
        }
    }

    async function fetchMessages(conversationId, page = 1) {
        if (
            pagination.isLoading ||
            (page > 1 && pagination.currentPage >= pagination.lastPage)
        ) {
            return;
        }
        pagination.isLoading = true;
        if (page > 1) showTopLoader();

        try {
            const response = await apiFetch(
                `/conversations/${conversationId}/messages?page=${page}`,
            );
            const paginator = response.data;
            const newMessages = paginator.data.reverse();
            if (page === 1) {
                const convData = conversations.find(
                    (c) => c.id === conversationId,
                );
                activeConversation = { ...convData, messages: newMessages };
            } else {
                const oldScrollHeight = chatContainer.scrollHeight;
                activeConversation.messages.unshift(...newMessages);
                renderMessages();
                chatContainer.scrollTop =
                    chatContainer.scrollHeight - oldScrollHeight;
            }

            pagination.currentPage = paginator.current_page;
            pagination.lastPage = paginator.last_page;

            if (page === 1) renderMessages();
        } catch (error) {
            console.error("Failed to fetch messages:", error);
        } finally {
            pagination.isLoading = false;
            hideTopLoader();
        }
    }

    // INI FUNGSI YANG HILANG TADI
    async function selectChat(id) {
        if (activeConversation && activeConversation.id === id) return;

        resetPagination();

        try {
            messagesWrap.innerHTML = `<div class="text-center text-slate-400 py-10">Loading messages...</div>`;
            await fetchMessages(id, 1);
            renderHistory(); // Render ulang history untuk update highlight
        } catch (error) {
            console.error("Failed to select chat:", error);
            messagesWrap.innerHTML = `<div class="text-center text-red-400 py-10">Failed to load messages.</div>`;
        }
        focusInput();
    }

    async function sendMessageToServer(content, conversationId) {
        return await apiFetch("/messages", {
            method: "POST",
            body: JSON.stringify({
                content: content,
                conversation_id: conversationId,
            }),
        });
    }

    async function createNewConversationOnServer() {
        return await apiFetch("/conversations", {
            method: "POST",
            body: JSON.stringify({ title: "New Chat" }),
        });
    }

    // -- Rendering -----------------------------------------------------------
    function renderHistory() {
        historyList.innerHTML = "";
        if (conversations.length === 0) {
            historyList.innerHTML = `<div class="text-sm text-slate-400 px-3 py-4">No chats yet.</div>`;
            return;
        }
        conversations.forEach((conv) => {
            const item = document.createElement("button");
            item.className = `w-full text-left px-3 py-2 rounded-md hover:bg-slate-50 transition flex items-start gap-3 ${activeConversation && conv.id === activeConversation.id
                    ? "bg-indigo-50"
                    : ""
                }`;
            item.innerHTML = `
                <div class="w-10 h-10 rounded-md flex items-center justify-center bg-slate-100 text-slate-600 font-medium flex-shrink-0">${conv.title.slice(0, 2)}</div>
                <div class="flex-1 overflow-hidden">
                    <div class="text-sm font-medium text-slate-800 truncate">${conv.title}</div>
                    <div class="text-xs text-slate-400 mt-0.5 truncate">${conv.messages_count || 0} messages</div>
                </div>`;
            // Pastikan event listener memanggil selectChat
            item.addEventListener("click", () => selectChat(conv.id));
            historyList.appendChild(item);
        });
    }

    function renderMessages() {
        messagesWrap.innerHTML = "";
        if (!activeConversation || activeConversation.messages.length === 0) {
            messagesWrap.innerHTML = `<div class="text-center text-slate-400 py-10">No messages yet. Send one to start!</div>`;
            return;
        }
        activeConversation.messages.forEach((msg) =>
            addMessageToUI(msg.sender, msg),
        );
    }

    function addMessageToUI(sender, msg) {
        const wrapper = document.createElement("div");
        wrapper.className = `flex items-end gap-3 ${sender === "user" ? "justify-end" : "justify-start"}`;
        const time = new Date(msg.created_at).toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
        const bubbleBaseClasses =
            "relative max-w-[80%] px-4 py-3 rounded-2xl text-sm leading-relaxed break-words shadow-sm";

        if (sender === "user") {
            wrapper.innerHTML = `<div class="order-1"><div class="${bubbleBaseClasses} bg-indigo-600 text-white" style="border-bottom-right-radius: 6px;">${escapeHtml(msg.content)}</div><div class="text-xs text-slate-400 mt-1 text-right">${time}</div></div>`;
        } else {
            wrapper.innerHTML = `<div class="order-2"><div class="${bubbleBaseClasses} bg-slate-100 text-slate-800" style="border-bottom-left-radius: 6px;">${escapeHtml(msg.content)}</div><div class="text-xs text-slate-400 mt-1">${time}</div></div>`;
        }
        messagesWrap.appendChild(wrapper);
        if (sender === "user") scrollToBottom(); // Hanya auto-scroll saat user mengirim pesan
    }

    // -- Helpers -------------------------------------------------------------
    const escapeHtml = (str) =>
        String(str)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;")
            .replaceAll("\n", "<br>");
    const focusInput = () => messageInput.focus();
    const scrollToBottom = () => {
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: "smooth",
        });
    };
    const showTopLoader = () => {
        const loader = document.createElement("div");
        loader.id = "top-loader";
        loader.className = "text-center py-2 text-slate-400";
        loader.innerText = "Loading older messages...";
        messagesWrap.prepend(loader);
    };
    const hideTopLoader = () => {
        const loader = $("#top-loader");
        if (loader) loader.remove();
    };
    const resetPagination = () => {
        pagination.currentPage = 1;
        pagination.lastPage = 1;
        pagination.isLoading = false;
    };

    // -- Event Listeners -----------------------------------------------------
    clearBtnTop.addEventListener("click", async () => {
        if (!activeConversation) return;

        if (confirm("Are you sure you want to delete this conversation?")) {
            try {
                await deleteConversation(activeConversation.id);

                // Hapus dari state frontend
                const deletedId = activeConversation.id;
                conversations = conversations.filter((c) => c.id !== deletedId);
                activeConversation = null;

                // Render ulang UI
                renderHistory();
                renderMessages();

                // Jika masih ada percakapan lain, pilih yang pertama
                if (conversations.length > 0) {
                    await selectChat(conversations[0].id);
                }
            } catch (error) {
                console.error("Failed to delete conversation:", error);
                alert("Could not delete the conversation.");
            }
        }
    });
    clearHistoryBtn.addEventListener("click", async () => {
        if (conversations.length === 0) return;

        if (
            confirm(
                "Are you sure you want to delete ALL conversations? This cannot be undone.",
            )
        ) {
            try {
                await clearAllConversations();

                // Reset state frontend
                conversations = [];
                activeConversation = null;

                // Render ulang UI ke kondisi kosong
                renderHistory();
                renderMessages();
            } catch (error) {
                console.error("Failed to clear history:", error);
                alert("Could not clear all conversations.");
            }
        }
    });

    async function handleSendMessage() {
        const text = messageInput.value.trim();
        if (!text) return;
        let currentConversationId = activeConversation
            ? activeConversation.id
            : null;
        if (!currentConversationId) {
            try {
                const newConvResponse = await createNewConversationOnServer();
                currentConversationId = newConvResponse.data.id;
                await fetchConversations(); // Ambil ulang semua conv biar yang baru muncul
                await selectChat(currentConversationId);
            } catch (error) {
                console.error("Failed to create new conversation:", error);
                return;
            }
        }
        addMessageToUI("user", {
            content: text,
            created_at: new Date().toISOString(),
        });
        messageInput.value = "";
        statusTyping.classList.remove("hidden");
        try {
            const response = await sendMessageToServer(
                text,
                currentConversationId,
            );
            const botMessage = response.data.bot_message;
            if (activeConversation.id === botMessage.conversation_id) {
                activeConversation.messages.push(response.data.user_message);
                activeConversation.messages.push(botMessage);
            }
            addMessageToUI("bot", botMessage);
        } catch (error) {
            console.error("Failed to send message:", error);
            addMessageToUI("bot", {
                content: "Sorry, an error occurred.",
                created_at: new Date().toISOString(),
            });
        } finally {
            statusTyping.classList.add("hidden");
        }
    }

    sendBtn.addEventListener("click", handleSendMessage);
    messageInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendBtn.click();
        }
    });
    newChatBtn.addEventListener("click", async () => {
        try {
            const newConvResponse = await createNewConversationOnServer();
            await fetchConversations();
            await selectChat(newConvResponse.data.id);
        } catch (e) {
            console.error(e);
        }
    });
    chatContainer.addEventListener("scroll", () => {
        if (!activeConversation) return;
        if (chatContainer.scrollTop === 0) {
            fetchMessages(activeConversation.id, pagination.currentPage + 1);
        }
    });

    // -- Bootstrapping --------------------------------------------------------
    (async function boot() {
        try {
            await fetchConversations();
        } catch (error) {
            console.error("Failed to initialize app:", error);
            historyList.innerHTML = `<div class="text-sm text-slate-400 px-3 py-4">Error loading chats.</div>`;
        }
        focusInput();
    })();
});
