<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OMMS - Welcome</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 items-center justify-center min-h-screen flex-col">
    <header class="w-full max-w-4xl text-sm mb-6 flex justify-end gap-4">
        <a href="{{ route('otp.verify') }}" class="px-5 py-1.5 border border-[#19140035] rounded-sm hover:border-[#1915014a] transition-all">Log in</a>
    </header>

    <main class="max-w-4xl w-full bg-white shadow-sm rounded-lg p-12 border border-[#1914001a] text-center">
        <h1 class="text-3xl font-bold mb-4">Welcome to OMMS</h1>
        <p class="text-[#706f6c] mb-8">Please log in to access your dashboard and manage your tasks.</p>
        
        <a href="{{ route('otp.verify') }}" class="bg-[#1b1b18] text-white px-10 py-3 rounded-md hover:bg-black font-medium transition-all shadow-sm">
            Get Started
        </a>
    </main>

    <footer class="mt-8 text-[#706f6c] text-xs">
        &copy; {{ date('Y') }} OMMS Management System. All rights reserved.
    </footer>
</body>
</html>