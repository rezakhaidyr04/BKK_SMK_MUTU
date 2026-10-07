<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#0a1633">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logos/mutu_logo.png') }}">

        <title>{{ $seoTitle ?? config('app.name', 'BKKMu') }}</title>
        <meta name="description" content="{{ $seoDescription ?? 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.' }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $seoTitle ?? config('app.name', 'BKKMu') }}">
        <meta property="og:description" content="{{ $seoDescription ?? 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary">
        <link rel="canonical" href="{{ url()->current() }}">

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
        {{-- ?v= bertambah setiap app-custom.css diubah agar browser tidak memakai cache lama --}}
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}?v=20261007a">
        {{-- Mobile experience layer (HP-first untuk pelamar) --}}
        <link rel="stylesheet" href="{{ asset('css/mobile.css') }}?v=20261007b">
        
        @stack('styles')
    </head>
    <body x-data="{ 
        sidebarOpen: {{ ($hideSidebar ?? false) ? 'false' : '(function () { const stored = localStorage.getItem("sidebarOpen"); if (stored !== null) return JSON.parse(stored); return window.innerWidth >= 1024; })()' }},
        init() {
            this.$watch('sidebarOpen', (val) => {
                localStorage.setItem('sidebarOpen', JSON.stringify(val));
            });
        }
    }" @resize.window="sidebarOpen = {{ ($hideSidebar ?? false) ? 'false' : '(window.innerWidth >= 1024 ? sidebarOpen : false)' }}" class="font-sans antialiased bg-neutral-50 text-neutral-900 @auth authenticated @endauth">
        {{-- Toast + Confirm portals --}}
        <x-ui.toast />
        <x-ui.confirm />
        
        <div class="min-h-screen bg-neutral-50 flex flex-col">
            @include('layouts.navigation', ['hideSidebar' => $hideSidebar ?? false])

            <!-- Content area: .app-main-wrapper TIDAK memiliki spacing agar konsisten -->
            <div :class="['app-main-wrapper transition-all duration-300', sidebarOpen ? 'lg:ml-64' : 'lg:ml-0']">
                <!-- Kompensasi Navbar hanya SEKALI di sini -->
                <main class="main-content">@isset($header)<header class="app-page-header relative overflow-hidden shadow-sm"><div class="pointer-events-none absolute inset-0"></div><div class="page-container py-4 relative">{{ $header }}</div></header>@elseif(View::hasSection('header'))<header class="app-page-header relative overflow-hidden shadow-sm"><div class="pointer-events-none absolute inset-0"></div><div class="page-container py-4 relative">@yield('header')</div></header>@endif

                    <!-- PAGE CONTENT -->
                    <div>
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
                <footer class="app-footer relative mt-auto overflow-hidden bg-[#0a1633] text-white">
                    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                        <div class="absolute -right-24 -top-32 h-96 w-96 rounded-full bg-blue-600/20 blur-3xl"></div>
                        <div class="absolute -bottom-40 left-1/4 h-80 w-80 rounded-full bg-cyan-500/10 blur-3xl"></div>
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px); background-size: 26px 26px; mask-image: linear-gradient(to left, black 0%, black 35%, transparent 75%); -webkit-mask-image: linear-gradient(to left, black 0%, black 35%, transparent 75%);"></div>
                    </div>
                    <div class="app-footer-body relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                        <div class="app-footer-grid grid gap-8 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1.2fr_1fr]">
                            <div class="app-footer-brand space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKKMu" class="w-9 h-9 rounded-xl object-cover ring-1 ring-white/20">
                                    <div>
                                        <h3 class="text-lg font-bold text-white">BKKMu</h3>
                                        <p class="text-xs text-blue-200/70">Platform karir pencari kerja SMK</p>
                                    </div>
                                </div>
                                <p class="text-xs leading-relaxed text-gray-400">Menghubungkan talenta muda dengan perusahaan terpercaya.</p>
                                <div class="flex items-center gap-2 pt-1">
                                    <a href="https://www.instagram.com/hubinmasmutucikampek.official/" target="_blank" rel="noopener" aria-label="Instagram" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-gray-300 transition hover:bg-white/20 hover:text-white">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                    <a href="https://whatsapp.com/channel/0029Vb6IdSh8V0tolT7nA61H" target="_blank" rel="noopener" aria-label="WhatsApp" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-gray-300 transition hover:bg-white/20 hover:text-white">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                </div>
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
                                <ul class="space-y-2.5 text-xs text-gray-400">
                                    <li class="flex items-start gap-2">
                                        <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-blue-300/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <a href="https://www.google.com/maps/search/SMK%20Muhammadiyah%202%20Cikampek%20Karawang/" target="_blank" rel="noopener" class="transition hover:text-white">SMK MUTU Cikampek<br>Cikampek, Jawa Barat</a>
                                    </li>
                                    <li>
                                        <a href="mailto:bkksmkmutu3@gmail.com" class="flex items-center gap-2 transition hover:text-white">
                                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-blue-300/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            bkksmkmutu3@gmail.com
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:+62267123456" class="flex items-center gap-2 transition hover:text-white">
                                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-blue-300/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            (0267) 123-456
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="app-footer-tagline flex items-center lg:justify-end">
                                <p class="font-serif text-xl italic leading-snug text-white/90">Bersama<br>Membangun Masa Depan</p>
                            </div>
                        </div>
                        <div class="app-footer-copy mt-8 border-t border-white/10 pt-4 text-xs text-gray-500 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <p>© {{ date('Y') }} BKKMu. Hak cipta dilindungi.</p>
                            <div class="flex flex-wrap gap-3">
                                <span class="opacity-70">Kebijakan Privasi</span>
                                <span class="opacity-70">Syarat & Ketentuan</span>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        @include('layouts.notification-toasts')
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
