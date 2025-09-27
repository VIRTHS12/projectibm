<aside id="sidebar"
    class="bg-white border-r border-slate-200 w-80 md:w-96 flex-shrink-0
                   transition-all duration-300 ease-in-out
                   fixed md:static left-0 top-0 bottom-0 z-30
                   md:translate-x-0 transform -translate-x-full">

    <div class="h-full flex flex-col">
        <div class="px-4 py-3 flex items-center justify-between border-b border-slate-100 flex-shrink-0">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="sidebar-content transition-opacity duration-200">
                    <div class="text-lg font-semibold truncate">NoWan Chat</div>
                    <div class="text-xs text-slate-400">Your friendly chatbot</div>
                </div>
            </div>

            <button id="sidebarToggle" aria-label="Toggle sidebar" class="p-2 rounded-md hover:bg-slate-100 transition">
                <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                </svg>
            </button>
        </div>

        <div class="p-4 border-b border-slate-100 flex-shrink-0 sidebar-content transition-opacity duration-200">
            <div class="flex gap-2">
                <input id="searchChats" type="text" placeholder="Search chats..."
                    class="w-full text-sm px-3 py-2 rounded-lg bg-slate-50 border border-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition" />
                <button id="newChatBtn"
                    class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700 transition">New</button>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto overflow-x-hidden px-2 py-3 sidebar-content transition-opacity duration-200"
            id="historyList">
        </div>

        <div class="px-4 py-3 border-t border-slate-100 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-sm px-3 py-2 rounded-md bg-slate-100 hover:bg-slate-200 transition">
                    Logout
                </button>
            </form>
        </div>

        <div
            class="mt-auto px-4 py-3 border-t border-slate-100 flex-shrink-0 sidebar-content transition-opacity duration-200">
            <button id="clearHistory"
                class="w-full text-sm px-3 py-2 rounded-md border border-slate-200 hover:bg-slate-50 transition">
                Clear All History
            </button>
        </div>
    </div>
</aside>
