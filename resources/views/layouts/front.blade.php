<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'IMEI Lin Store') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="flex items-center gap-2">
                                <x-application-logo class="block h-9 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
                                <span class="font-bold text-xl text-gray-800 dark:text-gray-200">IMEI Lin</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 sm:gap-6">
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-500 font-semibold px-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-500 font-semibold px-2">Log in</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>
        
        <footer class="bg-white dark:bg-gray-800 py-6 mt-12 border-t dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} IMEI Lin Store. All rights reserved.
            </div>
        </footer>
    </body>
</html>
