@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-white md:hidden">
        <div class="flex items-center gap-3">
            <button id="mobileSidebarOpen" class="p-2 rounded-md hover:bg-slate-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </button>
            <div>
                <div class="text-sm font-semibold">NoWan Chat</div>
                <div class="text-xs text-slate-400">Responsive & Clean</div>
            </div>
        </div>
        <div class="text-xs text-slate-500">Online</div>
    </div>

    <div class="hidden md:flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
        <div>
            <div class="text-lg font-semibold">Conversation</div>
            <div id="activeChatSubtitle" class="text-xs text-slate-400">Start a new conversation</div>
        </div>
        <button id="clearBtnTop"
            class="px-3 py-2 bg-slate-50 rounded-md border border-slate-100 hover:bg-slate-100 text-sm">Clear
            Chat</button>
    </div>

    <section id="chatArea" class="flex-1 overflow-auto p-6 chat-scroll bg-gradient-to-b from-white to-slate-50">
        <div id="messages" class="max-w-3xl mx-auto space-y-4">
        </div>
    </section>
    <x-input />
@endsection
