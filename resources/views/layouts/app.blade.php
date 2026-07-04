<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mutu Career Center') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
        
        @stack('styles')
        {{-- Prevent flash of light mode when dark mode is saved --}}
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="font-sans antialiased bg-background text-gray-900 dark:bg-gray-950 dark:text-gray-100 @auth authenticated @endauth">
        <div class="min-h-screen bg-background dark:bg-gray-950 flex flex-col">
            @include('layouts.navigation')

            <!-- Wrapper that shifts when sidebar toggles so main+footer stay aligned -->
            <div :class="['transition-all duration-300', sidebarOpen ? 'lg:ml-64' : 'lg:ml-0']" class="flex-1">
                <!-- Main Content with proper margin for sidebar and top nav -->
                <main class="main-content pt-16 flex-1">
                    <div class="fade-in">
                    @if(session('success') || session('error') || session('status'))
                        <div class="page-container pt-4">
                            @if(session('success'))
                                <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
                            @endif
                            @if(session('error'))
                                <x-ui.alert type="error">{{ session('error') }}</x-ui.alert>
                            @endif
                            @if(session('status') && !session('success'))
                                <x-ui.alert type="info">{{ session('status') }}</x-ui.alert>
                            @endif
                        </div>
                    @endif

                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 border-b border-gray-200/80 dark:border-gray-700 shadow-sm">
                            <div class="page-container py-5">
                                {{ $header }}
                            </div>
                        </header>
                    @elseif(View::hasSection('header'))
                        <header class="bg-white border-b border-gray-200/80 shadow-sm">
                            <div class="page-container py-5">
                                @yield('header')
                            </div>
                        </header>
                    @endif

                    @hasSection('content')
                        @yield('content')
                    @else
                        <div class="@if($fullBleed ?? false) @else page-container page-section @endif">
                            {{ $slot ?? '' }}
                        </div>
                    @endif
                </div>
                </main>

                <!-- Footer -->
                <footer class="mt-auto bg-gray-900 dark:bg-gray-950 text-white border-t border-gray-800 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="Mutu Career Center" class="w-8 h-8 rounded-xl object-cover">
                                    <div>
                                        <h3 class="text-sm font-bold">Mutu Career Center</h3>
                                        <p class="text-xs text-gray-400">Pusat Pengembangan Karir</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">Platform karir yang menghubungkan siswa dan alumni SMK dengan peluang kerja terbaik.</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Tautan Cepat</h4>
                                <ul class="space-y-1 text-xs text-gray-400">
                                    <li><a href="{{ route('jobs.index') }}" class="hover:text-white">Jelajahi Lowongan</a></li>
                                    <li><a href="{{ route('events.index') }}" class="hover:text-white">Acara</a></li>
                                    <li><a href="{{ route('news.index') }}" class="hover:text-white">Berita Karir</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Kontak</h4>
                                <ul class="space-y-1 text-xs text-gray-400">
                                    <li>SMK MUTU Cikampek</li>
                                    <li>Cikampek, Jawa Barat</li>
                                    <li><a href="mailto:bkk@smkmutu.sch.id" class="hover:text-white">bkk@smkmutu.sch.id</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="border-t border-gray-800 mt-4 pt-4 text-center text-xs text-gray-400">
                            &copy; {{ date('Y') }} Mutu Career Center. Hak cipta dilindungi.
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
