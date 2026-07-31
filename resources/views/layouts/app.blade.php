<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BKK SMK MUTU') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scroll reveal CSS -->
        <style>
            [data-reveal] {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity 0.55s cubic-bezier(0.4,0,0.2,1), transform 0.55s cubic-bezier(0.4,0,0.2,1);
            }
            [data-reveal].revealed {
                opacity: 1;
                transform: translateY(0);
            }
            [data-reveal-left] {
                opacity: 0;
                transform: translateX(-24px);
                transition: opacity 0.55s cubic-bezier(0.4,0,0.2,1), transform 0.55s cubic-bezier(0.4,0,0.2,1);
            }
            [data-reveal-left].revealed { opacity: 1; transform: translateX(0); }
            [data-reveal-right] {
                opacity: 0;
                transform: translateX(24px);
                transition: opacity 0.55s cubic-bezier(0.4,0,0.2,1), transform 0.55s cubic-bezier(0.4,0,0.2,1);
            }
            [data-reveal-right].revealed { opacity: 1; transform: translateX(0); }
            /* Stagger children */
            [data-stagger] > * { transition-delay: calc(var(--stagger-i, 0) * 80ms); }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
        
        @stack('styles')
        {{-- Prevent flash of light mode when dark mode is saved --}}
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024 ? sidebarOpen : false" class="font-sans antialiased bg-neutral-50 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-50 @auth authenticated @endauth">
        {{-- Toast notification portal --}}
        <x-ui.toast />
        
        <div class="min-h-screen bg-neutral-50 dark:bg-neutral-950 flex flex-col">
            @include('layouts.navigation')

            <!-- Wrapper that shifts when sidebar toggles so main+footer stay aligned -->
            <div :class="['transition-all duration-300', sidebarOpen ? 'lg:ml-64' : 'lg:ml-0']" class="flex-1">
                <!-- Main Content with proper margin for sidebar and top nav -->
                <main class="main-content pt-16 flex-1">
                    <div class="fade-in">

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
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-8 h-8 rounded-lg object-cover">
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">BKK SMK MUTU</h3>
                                        <p class="text-xs text-gray-400">Platform karir siswa & alumni SMK</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">Menghubungkan talenta muda dengan perusahaan terpercaya.</p>
                            </div>
                            <div>
                                <h4 class="mb-3 font-semibold text-white text-sm">Tautan Cepat</h4>
                                <ul class="space-y-2 text-xs text-gray-400">
                                    <li><a href="{{ route('jobs.index') }}" class="transition hover:text-white">Lowongan</a></li>
                                    <li><a href="{{ route('events.index') }}" class="transition hover:text-white">Acara</a></li>
                                    <li><a href="{{ route('news.index') }}" class="transition hover:text-white">Berita</a></li>
                                    <li><a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="mb-3 font-semibold text-white text-sm">Kontak</h4>
                                <ul class="space-y-2 text-xs text-gray-400">
                                    <li>SMK MUTU Cikampek</li>
                                    <li>Cikampek, Jawa Barat</li>
                                    <li><a href="mailto:bkk@smkmutu.sch.id" class="transition hover:text-white">bkk@smkmutu.sch.id</a></li>
                                    <li><a href="tel:+62267123456" class="transition hover:text-white">(0267) 123-456</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-800 pt-4 text-xs text-gray-500 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <p>© {{ date('Y') }} BKK SMK MUTU. Hak cipta dilindungi.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#" class="transition hover:text-white">Kebijakan Privasi</a>
                                <a href="#" class="transition hover:text-white">Syarat & Ketentuan</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        @stack('scripts')
        {{-- Scroll Reveal Script --}}
        <script>
        (function() {
            const all = document.querySelectorAll('[data-reveal],[data-reveal-left],[data-reveal-right]');
            if (!all.length) return;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('revealed'), i * 60);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            all.forEach(el => observer.observe(el));
            // Stagger children
            document.querySelectorAll('[data-stagger]').forEach(parent => {
                [...parent.children].forEach((child, i) => child.style.setProperty('--stagger-i', i));
            });
        })();
        </script>
    </body>
</html>
