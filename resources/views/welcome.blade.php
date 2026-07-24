<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BKK SMK MUTU - Platform Pengembangan Karir</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Local Tailwind CSS (no internet required) --}}
    <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-10 h-10 rounded-xl object-cover">
                            <span class="text-xl font-bold text-gray-900">BKK SMK MUTU</span>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Beranda</a>
                    <a href="#jobs" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Lowongan</a>
                    <a href="#trusted" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Perusahaan</a>
                    <a href="#features" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Tentang Kami</a>
                </div>
                
                <div class="flex items-center gap-4">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors hidden sm:block">
                            Dasbor
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors hidden sm:block">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Daftar
                        </a>
                    @endif
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors" aria-label="Toggle menu">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden bg-white border-b border-gray-200">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
                    Beranda
                </a>
                <a href="#jobs" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
                    Lowongan
                </a>
                <a href="#trusted" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
                    Perusahaan
                </a>
                <a href="#features" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors">
                    Tentang Kami
                </a>
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors sm:hidden">
                        Dasbor
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="sm:hidden">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors sm:hidden">
                        Masuk
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <main>
    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #4f46e5 100%); padding-top: 96px; padding-bottom: 0; min-height: 560px;">
        <div class="absolute inset-0 overflow-hidden" style="z-index:0; pointer-events:none;">
            <span class="hero-circle hero-circle-1"></span>
            <span class="hero-circle hero-circle-2"></span>
            <span class="hero-circle hero-circle-3"></span>
            <span class="hero-particle hero-particle-1"></span>
            <span class="hero-particle hero-particle-2"></span>
            <span class="hero-particle hero-particle-3"></span>
        </div>

        <div style="position:relative; z-index:1; max-width:1280px; margin:0 auto; padding:0 32px;">
            <div class="hero-main-layout">

                {{-- KIRI: teks --}}
                <div class="hero-text-col">
                    <div id="js-hero-greeting" class="hero-greeting" style="display:inline-flex; align-items:center; gap:8px; border-radius:9999px; border:1px solid rgba(255,255,255,0.25); background:rgba(255,255,255,0.12); padding:8px 20px; font-size:0.85rem; font-weight:600; color:#fff; backdrop-filter:blur(12px); margin-bottom:24px;">
                        👋 Selamat Datang di BKK SMK MUTU
                    </div>

                    <h1 style="font-size:clamp(1.6rem, 2.6vw, 2.6rem); font-weight:800; line-height:1.2; color:#fff; margin:0 0 16px; letter-spacing:-0.02em;">
                        Temukan Karier Impian Anda<br>Bersama <span style="color:#93c5fd;">BKK SMK MUTU</span>
                    </h1>

                    <p style="color:rgba(224,231,255,0.9); font-size:1rem; line-height:1.75; max-width:460px; margin:0 0 28px;">
                        Platform Bursa Kerja Khusus modern yang menghubungkan siswa dan alumni dengan perusahaan terbaik untuk membangun karier masa depan.
                    </p>

                    <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:32px;">
                        <a href="{{ route('register') }}" class="ui-btn ui-btn-primary ui-btn-lg hero-cta" aria-label="Mulai Gratis">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('jobs.index') }}" class="ui-btn ui-btn-white ui-btn-lg hero-cta secondary" aria-label="Lihat Lowongan">
                            Lihat Lowongan
                        </a>
                    </div>

                    <aside class="hero-info-bar" aria-label="Info ringkas BKK SMK MUTU">
                        <div style="overflow:hidden;">
                            <div class="hero-info-scroll" style="display:inline-flex; align-items:center; gap:24px; white-space:nowrap;">
                                <span class="hero-info-pill">🔥 5 Lowongan Baru Hari Ini</span>
                                <span class="hero-info-pill">📅 Job Fair 15 September 2026</span>
                                <span class="hero-info-pill">⭐ 15+ Alumni Diterima Bulan Ini</span>
                                <span class="hero-info-pill">📄 CV Builder Kini Tersedia</span>
                                <span class="hero-info-pill">🔥 5 Lowongan Baru Hari Ini</span>
                                <span class="hero-info-pill">📅 Job Fair 15 September 2026</span>
                                <span class="hero-info-pill">⭐ 15+ Alumni Diterima Bulan Ini</span>
                                <span class="hero-info-pill">📄 CV Builder Kini Tersedia</span>
                            </div>
                        </div>
                    </aside>
                </div>

                {{-- KANAN: foto siswa --}}
                <div class="hero-photo-col">
                    <div class="hero-illustration-wrap">

                        {{-- Glow lembut di belakang foto --}}
                        <div style="position:absolute; width:360px; height:360px; border-radius:50%; background:radial-gradient(circle, rgba(96,165,250,0.15) 0%, transparent 65%); bottom:0; left:50%; transform:translateX(-50%); z-index:0; pointer-events:none;"></div>

                        {{-- Foto siswa dalam frame modern glassmorphism --}}
                        <div style="position:relative; z-index:1; width:100%; max-width:440px; margin:0 auto; border-radius:32px; overflow:hidden; border:1px solid rgba(255,255,255,0.4); box-shadow: 0 30px 60px rgba(0,0,0,0.25), inset 0 2px 4px rgba(255,255,255,0.5); background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%); backdrop-filter: blur(16px); transform:translateY(10px);">
                            <div style="padding: 12px;">
                                <img src="{{ asset('images/foto_siswa/siswa.png') }}"
                                     alt="Ilustrasi siswa BKK SMK MUTU"
                                     loading="eager"
                                     style="width:100%; height:auto; object-fit:cover; display:block; border-radius: 20px; filter:contrast(1.02) brightness(1.02);">
                            </div>
                        </div>


                        {{-- Badge kanan atas --}}
                        <div class="hero-badge" data-index="1" style="position:absolute; top:12%; right:2%; z-index:3;">
                            <span>💼</span><span>150+ Lowongan Aktif</span>
                        </div>
                        {{-- Badge kiri atas --}}
                        <div class="hero-badge" data-index="2" style="position:absolute; top:28%; left:-4%; z-index:3;">
                            <span>📄</span><span>CV ATS Profesional</span>
                        </div>
                        {{-- Badge kanan bawah --}}
                        <div class="hero-badge" data-index="3" style="position:absolute; bottom:16%; right:4%; z-index:3;">
                            <span>🏆</span><span>93% Alumni Berhasil</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Trusted Companies (static assets) -->
    <section id="trusted" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-sm uppercase tracking-[0.32em] text-slate-400">Dipercaya oleh Perusahaan Terkemuka</p>
                <h2 class="mt-4 text-3xl font-semibold text-slate-900 sm:text-4xl">Jaringan mitra yang terus berkembang</h2>
                <p class="mt-3 text-base text-slate-500">Lebih dari <span class="font-semibold text-slate-1000">6</span> perusahaan mitra aktif mendukung lulusan SMK MUTU.</p>
            </div>

            <div class="trusted-marquee-card overflow-hidden py-6">
                @if(!empty($partnerLogos))
                    <div class="trusted-marquee-track" aria-hidden="true">
                        @foreach($partnerLogos as $logo)
                            <div class="trusted-logo">
                                <img src="{{ asset($logo) }}" alt="" loading="lazy" onerror="this.style.display='none'" />
                            </div>
                        @endforeach
                    </div>
                    <div class="trusted-marquee-track" aria-hidden="true">
                        @foreach($partnerLogos as $logo)
                            <div class="trusted-logo">
                                <img src="{{ asset($logo) }}" alt="" loading="lazy" onerror="this.style.display='none'" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 px-6">
                        <div class="rounded-2xl bg-slate-100 p-6 text-center text-slate-500">Logo mitra belum tersedia</div>
                        <div class="rounded-2xl bg-slate-100 p-6 text-center text-slate-500">Logo mitra belum tersedia</div>
                        <div class="rounded-2xl bg-slate-100 p-6 text-center text-slate-500">Logo mitra belum tersedia</div>
                        <div class="rounded-2xl bg-slate-100 p-6 text-center text-slate-500">Logo mitra belum tersedia</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Why Choose BKK SMK MUTU -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Mengapa Memilih BKK SMK MUTU?</h2>
                <p class="mt-3 text-base text-slate-500">Platform lengkap untuk pengembangan karir Anda</p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                <!-- Feature 1: Pencocokan Cerdas -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">Pencocokan Cerdas</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Sistem rekomendasi pekerjaan berdasarkan keahlian dan minat Anda</p>
                </article>
                <!-- Feature 2: CV Ramah ATS -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">CV Ramah ATS</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Buat CV profesional yang dioptimalkan untuk Applicant Tracking System</p>
                </article>
                <!-- Feature 3: Pelacakan Lamaran -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">Pelacakan Lamaran</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Monitor status lamaran Anda secara real-time dengan timeline</p>
                </article>
                <!-- Feature 4: Rekomendasi AI -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-orange-50">
                        <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">Rekomendasi AI</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Dapatkan rekomendasi karir dan pelatihan sesuai profil Anda</p>
                </article>
                <!-- Feature 5: Terhubung Perusahaan -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">Terhubung Perusahaan</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Akses langsung ke perusahaan terpercaya dan peluang kerja terbaik</p>
                </article>
                <!-- Feature 6: Data Aman -->
                <article class="feature-card-v2 group">
                    <div class="feature-icon-circle bg-green-50">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 mt-4">Data Aman</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">Keamanan data Anda adalah prioritas utama kami</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Bagaimana Cara Kerjanya? -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Bagaimana Cara Kerjanya?</h2>
            </div>
            <div class="hiw-steps-grid" role="list">
                <!-- Step 1 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">1</div>
                        <div class="hiw-step-circle bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Daftar Akun</h4>
                    <p class="text-sm text-slate-500 mt-1">Buat akun gratis<br>dengan mudah</p>
                </div>
                <div class="hiw-connector" aria-hidden="true"></div>
                <!-- Step 2 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">2</div>
                        <div class="hiw-step-circle bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Lengkapi Profil</h4>
                    <p class="text-sm text-slate-500 mt-1">Isi profil dan unggah<br>CV terbaik Anda</p>
                </div>
                <div class="hiw-connector" aria-hidden="true"></div>
                <!-- Step 3 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">3</div>
                        <div class="hiw-step-circle bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Cari Lowongan</h4>
                    <p class="text-sm text-slate-500 mt-1">Temukan lowongan<br>yang sesuai dengan<br>keahlian Anda</p>
                </div>
                <div class="hiw-connector" aria-hidden="true"></div>
                <!-- Step 4 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">4</div>
                        <div class="hiw-step-circle bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Lamar Pekerjaan</h4>
                    <p class="text-sm text-slate-500 mt-1">Kirim lamaran dengan<br>satu klik</p>
                </div>
                <div class="hiw-connector" aria-hidden="true"></div>
                <!-- Step 5 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">5</div>
                        <div class="hiw-step-circle bg-blue-50">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Interview</h4>
                    <p class="text-sm text-slate-500 mt-1">Ikuti proses seleksi<br>dari perusahaan</p>
                </div>
                <div class="hiw-connector" aria-hidden="true"></div>
                <!-- Step 6 -->
                <div class="hiw-step" role="listitem" data-reveal>
                    <div class="hiw-step-icon-wrap">
                        <div class="hiw-step-badge">6</div>
                        <div class="hiw-step-circle bg-green-50">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-base font-bold text-slate-900 mt-5">Diterima Kerja</h4>
                    <p class="text-sm text-slate-500 mt-1">Selamat! Mulai karir<br>impian Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Bar -->
    <section class="py-6 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="stats-bar-v2">
                <div class="stat-item-v2">
                    <div class="stat-icon-v2 bg-blue-100">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-white" data-counter="25" data-suffix="+" aria-live="polite">0</p>
                        <p class="text-sm font-semibold text-white">Perusahaan Mitra</p>
                        <p class="text-xs text-blue-100 italic">Bergabung bersama kami</p>
                        <p class="text-xs text-blue-100 italic mt-1">6 perusahaan aktif</p>
                    </div>
                </div>
                <div class="stat-divider-v2"></div>
                <div class="stat-item-v2">
                    <div class="stat-icon-v2 bg-blue-100">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-white" data-counter="350" data-suffix="+" aria-live="polite">0</p>
                        <p class="text-sm font-semibold text-white">Alumni Terdaftar</p>
                        <p class="text-xs text-blue-100 italic">Telah membuat akun</p>
                        <p class="text-xs text-blue-100 italic mt-1">Siswa & alumni</p>
                    </div>
                </div>
                <div class="stat-divider-v2"></div>
                <div class="stat-item-v2">
                    <div class="stat-icon-v2 bg-blue-100">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-white" data-counter="120" data-suffix="+" aria-live="polite">0</p>
                        <p class="text-sm font-semibold text-white">Lowongan Dipublikasikan</p>
                        <p class="text-xs text-blue-100 italic">Setiap bulan diperbarui</p>
                        <p class="text-xs text-blue-100 italic mt-1">Lowongan aktif</p>
                    </div>
                </div>
                <div class="stat-divider-v2"></div>
                <div class="stat-item-v2">
                    <div class="stat-icon-v2 bg-blue-100">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-white" data-counter="93" data-suffix="%" aria-live="polite">0</p>
                        <p class="text-sm font-semibold text-white">Alumni Berhasil Bekerja</p>
                        <p class="text-xs text-blue-100 italic">Dalam 6 bulan terakhir</p>
                        <p class="text-xs text-blue-100 italic mt-1">Tingkat penempatan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Peluang Lowongan Terbaru -->
    @if(isset($jobs) && $jobs->count() > 0)
    <section id="jobs" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Peluang Lowongan Terbaru</h2>
                    <p class="text-sm text-slate-500 mt-1">Temukan kesempatan karir terbaik untuk Anda</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition hidden sm:inline-flex items-center gap-1">Lihat Semua Lowongan <span>→</span></a>
            </div>
            <div class="job-carousel-wrapper">
                <button class="job-carousel-btn job-carousel-prev" aria-label="Previous">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="job-carousel-track">
                    @foreach($jobs->take(4) as $index => $job)
                    @php
                        $colors = ['bg-blue-600', 'bg-teal-600', 'bg-red-500', 'bg-yellow-500'];
                        $bgColor = $colors[$index % 4];
                    @endphp
                    <article class="job-card-v2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="job-initial-v2 {{ $bgColor }}">{{ strtoupper(substr($job->company->name ?? 'B', 0, 1)) }}</div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">{{ $job->title }}</h3>
                                    <p class="text-sm text-slate-500">{{ $job->company->name ?? 'Perusahaan Mitra' }}</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-300 hover:text-blue-600 transition" aria-label="Bookmark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">{{ $job->location }}</span>
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">{{ \App\Support\Label::jobType($job->job_type) ?? 'Penuh Waktu' }}</span>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm font-semibold text-slate-900">{{ $job->salary ? 'Rp ' . number_format($job->salary, 0, ',', '.') : 'Negosiasi' }}</p>
                            <p class="text-xs text-slate-400">Deadline: {{ \Carbon\Carbon::parse($job->deadline)->translatedFormat('j M Y') }}</p>
                        </div>
                        <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Detail <span>→</span></a>
                    </article>
                    @endforeach
                </div>
                <button class="job-carousel-btn job-carousel-next" aria-label="Next">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition">Lihat Semua Lowongan</a>
            </div>
        </div>
    </section>
    @else
    <section id="jobs" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Peluang Lowongan Terbaru</h2>
                    <p class="text-sm text-slate-500 mt-1">Temukan kesempatan karir terbaik untuk Anda Demi Masa Depan Yang Cerah</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition hidden sm:inline-flex items-center gap-1">Lihat Semua Lowongan <span>→</span></a>
            </div>
            <div class="job-carousel-wrapper">
                <button class="job-carousel-btn job-carousel-prev" aria-label="Previous">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="job-carousel-track">
                    <!-- Sample Card 1 -->
                    <article class="job-card-v2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="job-initial-v2 bg-blue-600">M</div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Operator Produksi</h3>
                                    <p class="text-sm text-slate-500">PT Maju Bersama</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-300 hover:text-blue-600 transition" aria-label="Bookmark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Karawang Barat</span>
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Penuh Waktu</span>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm font-semibold text-slate-900">Rp 4.000.000 - 5.200.000</p>
                            <p class="text-xs text-slate-400">Deadline: 31 Jul 2026</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Detail <span>→</span></a>
                    </article>
                    <!-- Sample Card 2 -->
                    <article class="job-card-v2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="job-initial-v2 bg-teal-600">T</div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Junior Web Developer</h3>
                                    <p class="text-sm text-slate-500">PT Teknologi Nusantara</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-300 hover:text-blue-600 transition" aria-label="Bookmark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Karawang Timur</span>
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Penuh Waktu</span>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm font-semibold text-slate-900">Rp 4.500.000 - 6.500.000</p>
                            <p class="text-xs text-slate-400">Deadline: 28 Jul 2026</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Detail <span>→</span></a>
                    </article>
                    <!-- Sample Card 3 -->
                    <article class="job-card-v2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="job-initial-v2 bg-red-500">R</div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Staf Administrasi</h3>
                                    <p class="text-sm text-slate-500">PT Ritel Karawang</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-300 hover:text-blue-600 transition" aria-label="Bookmark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Karawang</span>
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Penuh Waktu</span>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm font-semibold text-slate-900">Rp 3.500.000 - 4.500.000</p>
                            <p class="text-xs text-slate-400">Deadline: 25 Jul 2026</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Detail <span>→</span></a>
                    </article>
                    <!-- Sample Card 4 -->
                    <article class="job-card-v2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="job-initial-v2 bg-yellow-500">K</div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Resepsionis Hotel</h3>
                                    <p class="text-sm text-slate-500">PT Karawang Hospitality</p>
                                </div>
                            </div>
                            <button type="button" class="text-slate-300 hover:text-blue-600 transition" aria-label="Bookmark">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Karawang</span>
                            <span class="job-tag-v2 bg-blue-50 text-blue-700">Penuh Waktu</span>
                        </div>
                        <div class="mt-4 space-y-1">
                            <p class="text-sm font-semibold text-slate-900">Rp 3.800.000 - 4.800.000</p>
                            <p class="text-xs text-slate-400">Deadline: 30 Jul 2026</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Detail <span>→</span></a>
                    </article>
                </div>
                <button class="job-carousel-btn job-carousel-next" aria-label="Next">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition">Lihat Semua Lowongan</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Call To Action - Siap Memulai Karir Anda? -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cta-banner-v2">
                <div class="cta-content-v2">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-6">Siap Memulai Karir Anda?</h2>
                    <p class="text-lg sm:text-xl text-white max-w-xl leading-relaxed mb-8">Bergabunglah bersama ribuan alumni yang telah menemukan pekerjaan impian melalui BKK SMK MUTU.</p>
                    @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-blue-700 text-base sm:text-lg font-bold shadow-xl shadow-blue-900/30 hover:bg-blue-50 hover:shadow-2xl hover:shadow-blue-900/40 transition-all duration-300 transform hover:-translate-y-1">
                        Daftar Sekarang Gratis <span>→</span>
                    </a>
                    @else
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-white text-blue-700 text-base sm:text-lg font-bold shadow-xl shadow-blue-900/30 hover:bg-blue-50 hover:shadow-2xl hover:shadow-blue-900/40 transition-all duration-300 transform hover:-translate-y-1">
                        Lihat Lowongan <span>→</span>
                    </a>
                    @endguest
                </div>
                <div class="cta-illustration-v2">
                    <img src="{{ asset('images/foto_siswa/siswa.png') }}" alt="Ilustrasi siswa BKK SMK MUTU" class="cta-illustration-img">
                </div>
            </div>
        </div>
    </section>
    </main>

    <!-- Footer -->
    <footer id="footer" class="bg-slate-950 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-10 h-10 rounded-xl object-cover">
                        <div>
                            <h3 class="text-xl font-semibold text-white">BKK SMK MUTU</h3>
                            <p class="text-sm text-slate-400">Platform karir khusus untuk siswa dan alumni SMK.</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400">Menghubungkan talenta muda dengan perusahaan terpercaya untuk karir yang lebih cepat dan profesional.</p>
                </div>
                <div>
                    <h4 class="mb-4 font-semibold text-white">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('jobs.index') }}" class="transition hover:text-white">Lowongan</a></li>
                        <li><a href="{{ route('events.index') }}" class="transition hover:text-white">Acara</a></li>
                        <li><a href="{{ route('news.index') }}" class="transition hover:text-white">Berita</a></li>
                        <li><a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 font-semibold text-white">Job Seeker</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('register') }}" class="transition hover:text-white">Daftar</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="transition hover:text-white">Cari Lowongan</a></li>
                        <li><a href="{{ route('news.index') }}" class="transition hover:text-white">Tips Karir</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 font-semibold text-white">Contact</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li>SMK MUTU Cikampek</li>
                        <li>Cikampek, Jawa Barat</li>
                        <li><a href="mailto:bkk@smkmutu.sch.id" class="transition hover:text-white">bkk@smkmutu.sch.id</a></li>
                        <li><a href="tel:+62267123456" class="transition hover:text-white">(0267) 123-456</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-slate-800 pt-8 text-sm text-slate-500 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <p>© {{ date('Y') }} BKK SMK MUTU. Hak cipta dilindungi.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="transition hover:text-white">Privacy Policy</a>
                    <a href="#" class="transition hover:text-white">Terms</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var greetingEl = document.getElementById('js-hero-greeting');
            if (greetingEl) {
                var hour = new Date().getHours();
                var greet = 'Selamat Datang di BKK SMK MUTU';
                if (hour >= 4 && hour < 11) {
                    greet = 'Selamat Pagi di BKK SMK MUTU';
                } else if (hour >= 11 && hour < 15) {
                    greet = 'Selamat Siang di BKK SMK MUTU';
                } else if (hour >= 15 && hour < 18) {
                    greet = 'Selamat Sore di BKK SMK MUTU';
                } else {
                    greet = 'Selamat Malam di BKK SMK MUTU';
                }
                greetingEl.textContent = '👋 ' + greet;
                window.requestAnimationFrame(function () {
                    greetingEl.classList.add('show');
                });
            }

            var revealItems = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window) {
                var revealObserver = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2 });
                revealItems.forEach(function (item) {
                    revealObserver.observe(item);
                });
            } else {
                revealItems.forEach(function (item) {
                    item.classList.add('in-view');
                });
            }

            var counters = document.querySelectorAll('[data-counter]');
            var counterObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.4 });
            counters.forEach(function (counter) {
                counterObserver.observe(counter);
            });

            function animateCounter(element) {
                var raw = element.dataset.counter || '0';
                var target = parseInt(String(raw).replace(/\D/g, ''), 10) || 0;
                var suffix = element.dataset.suffix || '';
                // duration scales with magnitude but stays within sensible bounds
                var duration = Math.min(2200, Math.max(900, Math.round(900 + (Math.log10(target + 1) || 0) * 500)));
                var startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var current = Math.floor(progress * target);
                    element.textContent = formatNumber(current) + suffix;
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        element.textContent = formatNumber(target) + suffix;
                    }
                }
                window.requestAnimationFrame(step);
            }

            // Job carousel navigation
            document.querySelectorAll('.job-carousel-wrapper').forEach(function(wrapper) {
                var track = wrapper.querySelector('.job-carousel-track');
                var prevBtn = wrapper.querySelector('.job-carousel-prev');
                var nextBtn = wrapper.querySelector('.job-carousel-next');
                if (track && prevBtn && nextBtn) {
                    var scrollAmount = 300;
                    prevBtn.addEventListener('click', function() {
                        track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                    });
                    nextBtn.addEventListener('click', function() {
                        track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    });
                }
            });

            // Counter formatting with dots (Indonesian style)
            function formatNumber(num) {
                var n = parseInt(num, 10);
                if (isNaN(n)) return String(num);
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });
    </script>
</body>
</html>
