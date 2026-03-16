<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#f7f7f7] text-gray-900" x-data="{ sidebarOpen: false }">
    @include('users.layouts.header')

    <div class="relative">
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 bg-black/40 z-40 lg:hidden"
            @click="sidebarOpen = false"
            style="display: none;"
        ></div>

        <div class="max-w-[1600px] mx-auto lg:flex">
            @include('users.layouts.sidebar')

            <main class="flex-1 min-h-[calc(100vh-80px)]">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>