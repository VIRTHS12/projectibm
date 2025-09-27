<div class="bg-white border-t border-slate-100 px-4 py-3">
    <div class="max-w-3xl mx-auto flex items-center gap-3">
        <div class="flex-1">
            <div class="relative">
                <textarea id="messageInput" rows="1" placeholder="Type your message..."
                    class="w-full resize-none rounded-xl px-4 py-3 pr-16 text-sm bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition shadow-sm"></textarea>
                <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <button id="sendBtn"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between mt-2 text-xs text-slate-400">
                <div>Press Enter to send • Shift+Enter for newline</div>
                <div id="statusTyping" class="hidden">Bot is typing…</div>
            </div>
        </div>
    </div>
</div>
