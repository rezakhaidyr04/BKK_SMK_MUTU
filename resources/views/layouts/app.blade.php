<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BKK SMK MUTU') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
        
        @stack('styles')
    </head>
    <body x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="font-sans antialiased bg-background text-gray-900 @auth authenticated @endauth">
        <div class="min-h-screen bg-background flex flex-col">
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
                        <header class="bg-white border-b border-gray-200/80 shadow-sm">
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
                <footer class="mt-auto bg-slate-950 text-slate-200 border-t border-slate-800">
                    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 transition-all duration-300">
                    <div class="grid gap-8 lg:grid-cols-[2.4fr_1fr_1fr]">
                        <div class="space-y-4">
<div class="flex items-start gap-3">
                                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="flex h-12 w-12 items-center justify-center rounded-2xl object-cover">
                                <div>

                                    <h3 class="text-lg font-semibold text-white">BKK SMK MUTU</h3>
                                    <p class="text-sm leading-7 text-slate-400">Pusat Pengembangan Karir yang membantu siswa dan alumni menemukan peluang kerja berkualitas.</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('home') }}#social" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-800 text-slate-100 transition-colors hover:bg-slate-700" aria-label="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="{{ route('home') }}#social" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-800 text-slate-100 transition-colors hover:bg-slate-700" aria-label="Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                                <a href="{{ route('home') }}#social" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-800 text-slate-100 transition-colors hover:bg-slate-700" aria-label="GitHub">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-400 mb-4">Tautan Cepat</h4>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">Cari Lowongan</a></li>
                                <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Acara</a></li>
                                <li><a href="{{ route('news.index') }}" class="hover:text-white transition-colors">Berita Karir</a></li>
                                <li><a href="{{ route('home') }}#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-400 mb-4">Kontak</h4>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li>SMK MUTU Cikampek</li>
                                <li>Jl. Pendidikan No. 123</li>
                                <li>Cikampek, Jawa Barat</li>
                                <li><a href="mailto:bkk@smkmutu.sch.id" class="hover:text-white transition-colors">bkk@smkmutu.sch.id</a></li>
                                <li><a href="tel:+622671234567" class="hover:text-white transition-colors">(0267) 123-4567</a></li>
                            </ul>
                        </div>
                    </div>
                    </div>
                    <div class="border-t border-slate-800 px-4 py-5 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} BKK SMK MUTU CIKAMPEK. Hak cipta dilindungi.
                    </div>
                </footer>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
