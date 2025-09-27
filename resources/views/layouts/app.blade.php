<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modern Chat UI — Revised</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('script.js') }}"></script>
</head>

<body class="h-screen bg-slate-50 antialiased text-slate-800">

    <div id="app" class="h-screen flex overflow-hidden">
        <x-sidebar />
        <div id="overlay"
            class="fixed inset-0 bg-black/40 z-20 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden">
        </div>

        <main id="mainContent" class="flex-1 flex flex-col transition-all duration-300 ease-in-out">
            @yield('content')
        </main>
    </div>
</body>
</html>
