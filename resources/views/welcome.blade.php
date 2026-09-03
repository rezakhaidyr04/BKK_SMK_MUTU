<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BKK SMK MUTU - Platform Pengembangan Karir</title>
    <meta name="description" content="Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="BKK SMK MUTU - Platform Pengembangan Karir">
    <meta property="og:description" content="Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v={{ filemtime(public_path('css/welcome.css')) }}">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>

    <!-- Navigation -->
    <header>
        <nav class="navbar" id="navbar">
            <div class="container">
                <div class="navbar-inner">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" width="36" height="36">
                        <span>BKK SMK MUTU</span>
                    </a>

                    <!-- Actions -->
                    <div class="navbar-actions">
                        @if(auth()->check())
                            <a href="{{ route('dashboard') }}" class="btn-dashboard">Dasbor</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline-form">
                                @csrf
                                <button type="submit" class="btn-logout">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Masuk
                            </a>
                        @endif

                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="navbar-toggle" aria-label="Toggle menu">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div class="mobile-menu" :class="{ 'is-open': mobileMenuOpen }">
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}">Dasbor</a>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endif
            </div>
        </nav>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero" id="hero">
            <!-- Decorative Background -->
            <div class="hero-deco" aria-hidden="true">
                <div class="hero-orb hero-orb-1"></div>
                <div class="hero-orb hero-orb-2"></div>
                <div class="hero-orb hero-orb-3"></div>
                <div class="hero-grid-pattern"></div>
                <div class="hero-dots"></div>
            </div>
            <div class="container">
                <div class="hero-inner">

                    <!-- Left: Content -->
                    <div class="hero-content">
                        <!-- Greeting Badge -->
                        <div id="js-hero-greeting" class="hero-badge">
                            <span aria-hidden="true">👋</span> Selamat Datang di BKK SMK MUTU
                        </div>

                        <!-- Heading -->
                        <h1 class="hero-title">
                            Temukan Karier Impian Anda<br>Bersama <span class="highlight">BKK SMK MUTU</span>
                        </h1>

                        <!-- Description -->
                        <p class="hero-desc">
                            Platform Bursa Kerja Khusus modern yang menghubungkan pencari kerja dengan perusahaan terbaik untuk membangun karier masa depan.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="hero-cta">
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                Mulai Gratis
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline">
                                Lihat Lowongan
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>

                        <!-- Stats Row -->
                        <div class="hero-stats">
                            <div class="hero-stat">
                                <span class="hero-stat-number" data-counter="{{ $companiesCount ?? 10 }}" data-suffix="+">0</span>
                                <span class="hero-stat-label">Perusahaan Mitra</span>
                            </div>
                            <div class="hero-stat">
                                <span class="hero-stat-number" data-counter="{{ $activeJobsCount ?? 150 }}" data-suffix="+">0</span>
                                <span class="hero-stat-label">Lowongan Aktif</span>
                            </div>
                            <div class="hero-stat">
                                <span class="hero-stat-number" data-counter="{{ $usersCount ?? 13 }}" data-suffix="+">0</span>
                                <span class="hero-stat-label">Ribu Pencari Kerja</span>
                            </div>
                            <div class="hero-stat">
                                <span class="hero-stat-number" data-counter="{{ $successRate ?? 31 }}" data-suffix="%">0</span>
                                <span class="hero-stat-label">Tingkat Keberhasilan</span>
                            </div>
                        </div>

                        <!-- Info Items -->
                        <div class="hero-info">
                            <div class="hero-info-item">
                                <svg width="16" height="16" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>5 Lowongan Baru Hari Ini</span>
                            </div>
                            <div class="hero-info-item">
                                <svg width="16" height="16" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Job Fair 15 September 2026</span>
                            </div>
                            <div class="hero-info-item">
                                <svg width="16" height="16" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Lowongan baru setiap hari</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Visual -->
                    <div class="hero-visual">
                        <div class="hero-image">
                            <picture>
                                <source srcset="{{ asset('images/foto_siswa/siswa.webp') }}" type="image/webp">
                                <img src="{{ asset('images/foto_siswa/siswa.png') }}" alt="Ilustrasi pencari kerja BKK SMK MUTU" loading="eager" fetchpriority="high">
                            </picture>

                            <!-- Floating Card 1 -->
                            <div class="floating-card floating-card-1">
                                <div class="floating-card-icon blue">
                                    <svg width="16" height="16" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="floating-card-text">150+ Lowongan Aktif</span>
                            </div>
                            <!-- Floating Card 2 -->
                            <div class="floating-card floating-card-2">
                                <div class="floating-card-icon green">
                                    <svg width="16" height="16" fill="none" stroke="#16a34a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="floating-card-text">CV ATS Profesional</span>
                            </div>
                            <!-- Floating Card 3 -->
                            <div class="floating-card floating-card-3">
                                <div class="floating-card-icon yellow">
                                    <svg width="16" height="16" fill="none" stroke="#ca8a04" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <span class="floating-card-text">Ribuan Lamaran</span>
                                    <span class="floating-card-sub">Terkirim</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Cards -->
        <section class="stats-section">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card" data-reveal>
                        <div class="stat-card-icon">
                            <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <span class="stat-card-number" data-counter="{{ $companiesCount ?? 10 }}" data-suffix="+">0</span>
                            <span class="stat-card-title">Perusahaan Mitra</span>
                            <span class="stat-card-desc">Bergabung bersama kami</span>
                        </div>
                    </div>
                    <div class="stat-card" data-reveal>
                        <div class="stat-card-icon">
                            <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="stat-card-number" data-counter="{{ $activeJobsCount ?? 150 }}" data-suffix="+">0</span>
                            <span class="stat-card-title">Lowongan Aktif</span>
                            <span class="stat-card-desc">Peluang baru setiap hari</span>
                        </div>
                    </div>
                    <div class="stat-card" data-reveal>
                        <div class="stat-card-icon">
                            <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="stat-card-number" data-counter="{{ $usersCount ?? 13 }}" data-suffix="+">0</span>
                            <span class="stat-card-title">Pencari Kerja</span>
                            <span class="stat-card-desc">Tumbuh dengan peluang</span>
                        </div>
                    </div>
                    <div class="stat-card" data-reveal>
                        <div class="stat-card-icon">
                            <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <span class="stat-card-number" data-counter="{{ $successRate ?? 31 }}" data-suffix="%">0</span>
                            <span class="stat-card-title">Tingkat Keberhasilan</span>
                            <span class="stat-card-desc">Pengguna terserap kerja</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partner Logos -->
        <section class="partners-section">
            <div class="container">
                <p class="partners-label">Didukung jaringan perusahaan mitra untuk membuka lebih banyak peluang kerja.</p>
                <div class="partner-image-wrap">
                    <img
                        src="{{ asset('images/perusahaan/perusahaan.webp') }}"
                        alt="Perusahaan mitra BKK SMK MUTU"
                        class="partner-image"
                        loading="lazy"
                    >
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="features-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Mengapa Memilih BKK SMK MUTU?</h2>
                    <p class="section-subtitle">Platform lengkap untuk pengembangan karier Anda</p>
                </div>
                <div class="features-grid">
                    <!-- Feature 1 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">01</span>
                        <div class="feature-icon gradient-blue">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="feature-title">Pencocokan Cerdas</h3>
                        <p class="feature-desc">Sistem AI mencocokkan pekerjaan berdasarkan keahlian dan minat Anda</p>
                    </article>
                    <!-- Feature 2 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">02</span>
                        <div class="feature-icon gradient-emerald">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="feature-title">CV Ramah ATS</h3>
                        <p class="feature-desc">Buat CV profesional yang dioptimalkan untuk Applicant Tracking System</p>
                    </article>
                    <!-- Feature 3 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">03</span>
                        <div class="feature-icon gradient-violet">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="feature-title">Pelacakan Lamaran</h3>
                        <p class="feature-desc">Monitor status lamaran Anda secara real-time dengan mudah</p>
                    </article>
                    <!-- Feature 4 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">04</span>
                        <div class="feature-icon gradient-amber">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <h3 class="feature-title">Rekomendasi AI</h3>
                        <p class="feature-desc">Dapatkan rekomendasi karir dan pelatihan sesuai profil Anda</p>
                    </article>
                    <!-- Feature 5 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">05</span>
                        <div class="feature-icon gradient-rose">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="feature-title">Terhubung Perusahaan</h3>
                        <p class="feature-desc">Akses langsung ke perusahaan terpercaya dan peluang kerja terbaik</p>
                    </article>
                    <!-- Feature 6 -->
                    <article class="feature-card" data-reveal>
                        <span class="feature-number">06</span>
                        <div class="feature-icon gradient-teal">
                            <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="feature-title">Data Aman</h3>
                        <p class="feature-desc">Keamanan data Anda adalah prioritas utama kami</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials-section">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Kata Mereka</span>
                    <h2 class="section-title">Dipercaya oleh Ribu Pencari Kerja</h2>
                    <p class="section-subtitle">Cerita nyata dari mereka yang telah berhasil menemukan karir impian</p>
                </div>
                <div class="testimonials-grid">
                    @forelse($approvedReviews as $review)
                        <article class="testimonial-card @if($review->featured) featured @endif" data-reveal>
                            @if($review->featured)
                                <div class="testimonial-badge-popular">Populer</div>
                            @endif
                            <div class="testimonial-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg width="16" height="16" fill="{{ $i <= $review->rating ? '#f59e0b' : '#d1d5db' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @endfor
                            </div>
                            <p class="testimonial-text">"{{ Str::limit($review->comment, 180) }}"</p>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar" style="background: linear-gradient(135deg, {{ ['#3b82f6', '#16a34a', '#f59e0b', '#8b5cf6', '#ec4899'][$loop->index % 5] }}, {{ ['#2563eb', '#10b981', '#d97706', '#7c3aed', '#db2777'][$loop->index % 5] }});">
                                    {{ strtoupper(substr($review->display_name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="testimonial-name">{{ $review->display_name }}</span>
                                    <span class="testimonial-role">
                                        @if($review->job_title && $review->company_name)
                                            {{ $review->job_title }} @ {{ $review->company_name }}
                                        @else
                                            Pengguna BKK SMK MUTU
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                            <p style="color: #64748b; font-size: 1.125rem;">Tidak ada review yang ditampilkan saat ini.</p>
                        </div>
                    @endforelse
                </div>
                <div class="testimonials-stats">
                    <div class="testimonials-stat">
                        <span class="testimonials-stat-number">{{ number_format($averageRating, 1) }}</span>
                        <div class="testimonials-stat-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="14" height="14" fill="#f59e0b" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                        </div>
                        <span class="testimonials-stat-label">Rating Pengguna</span>
                    </div>
                    <div class="testimonials-stat">
                        <span class="testimonials-stat-number">{{ $totalReviews }}{{ $totalReviews > 0 ? '+' : '' }}</span>
                        <span class="testimonials-stat-label">Ulasan Positif</span>
                    </div>
                    <div class="testimonials-stat">
                        <span class="testimonials-stat-number">{{ $satisfactionPercentage }}%</span>
                        <span class="testimonials-stat-label">Puas dengan Layanan</span>
                    </div>
                </div>

                <!-- Call to Action: Leave Review -->
                <div style="margin-top: 3rem; text-align: center; padding: 2rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1)); border-radius: 1rem; border: 1px solid rgba(59, 130, 246, 0.2);">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Punya Pengalaman Positif?</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.95rem;">Bagikan cerita sukses Anda dan bantu pencari kerja lain untuk membuat keputusan terbaik</p>
                    @auth
                        <a href="{{ route('reviews.create') }}" class="ui-btn ui-btn-primary" style="display: inline-block;">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24" style="display: inline-block; margin-right: 0.5rem;">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            Bagikan Ulasan Anda
                        </a>
                    @else
                        <p style="font-size: 0.9rem; color: #6b7280; margin-bottom: 1rem;">Silakan login untuk membagikan ulasan Anda</p>
                        <div style="display: flex; gap: 1rem; justify-content: center;">
                            <a href="{{ route('login') }}" class="ui-btn ui-btn-secondary" style="display: inline-block;">Masuk</a>
                            <a href="{{ route('register') }}" class="ui-btn ui-btn-primary" style="display: inline-block;">Daftar Gratis</a>
                        </div>
                    @endauth
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="timeline-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Bagaimana Cara Kerjanya?</h2>
                </div>
                <div class="timeline-wrap" role="list">
                    <!-- Step 1 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number">1</span>
                            <div class="timeline-icon">
                                <svg width="32" height="32" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Daftar Akun</h4>
                        <p class="timeline-desc">Buat akun gratis<br>dengan mudah</p>
                    </div>
                    <div class="timeline-connector" aria-hidden="true"></div>
                    <!-- Step 2 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number">2</span>
                            <div class="timeline-icon">
                                <svg width="32" height="32" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Lengkapi Profil</h4>
                        <p class="timeline-desc">Isi profil dan unggah<br>CV terbaik Anda</p>
                    </div>
                    <div class="timeline-connector" aria-hidden="true"></div>
                    <!-- Step 3 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number">3</span>
                            <div class="timeline-icon">
                                <svg width="32" height="32" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Cari Lowongan</h4>
                        <p class="timeline-desc">Temukan lowongan<br>sesuai minat Anda</p>
                    </div>
                    <div class="timeline-connector" aria-hidden="true"></div>
                    <!-- Step 4 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number">4</span>
                            <div class="timeline-icon">
                                <svg width="32" height="32" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Lamar Pekerjaan</h4>
                        <p class="timeline-desc">Kirim lamaran dengan<br>satu klik mudah</p>
                    </div>
                    <div class="timeline-connector" aria-hidden="true"></div>
                    <!-- Step 5 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number">5</span>
                            <div class="timeline-icon">
                                <svg width="32" height="32" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Interview</h4>
                        <p class="timeline-desc">Ikuti proses seleksi<br>dari perusahaan</p>
                    </div>
                    <div class="timeline-connector" aria-hidden="true"></div>
                    <!-- Step 6 -->
                    <div class="timeline-step" role="listitem" data-reveal>
                        <div class="timeline-circle-wrap">
                            <span class="timeline-number success">6</span>
                            <div class="timeline-icon success">
                                <svg width="32" height="32" fill="none" stroke="#16a34a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h4 class="timeline-title">Diterima Kerja</h4>
                        <p class="timeline-desc">Selamat! Mulai karir<br>impian Anda</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blue Stats Banner -->
        <section class="blue-banner">
            <div class="container">
                <div class="blue-banner-grid">
                    <div class="blue-stat-card">
                        <div class="blue-stat-icon">
                            <svg width="22" height="22" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <span class="blue-stat-number" data-counter="{{ $companiesCount ?? 3 }}" data-suffix="+">0</span>
                            <span class="blue-stat-title">Pencapaian Mitra</span>
                            <span class="blue-stat-desc">Bergabung bersama kami</span>
                        </div>
                    </div>
                    <div class="blue-stat-card">
                        <div class="blue-stat-icon">
                            <svg width="22" height="22" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="blue-stat-number" data-counter="{{ $usersCount ?? 4 }}" data-suffix="+">0</span>
                            <span class="blue-stat-title">Pengguna Terdaftar</span>
                            <span class="blue-stat-desc">Tumbuh dengan peluang</span>
                        </div>
                    </div>
                    <div class="blue-stat-card">
                        <div class="blue-stat-icon">
                            <svg width="22" height="22" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="blue-stat-number" data-counter="{{ $activeJobsCount ?? 3 }}" data-suffix="+">0</span>
                            <span class="blue-stat-title">Lowongan Aktif</span>
                            <span class="blue-stat-desc">Peluang baru setiap hari</span>
                        </div>
                    </div>
                    <div class="blue-stat-card">
                        <div class="blue-stat-icon">
                            <svg width="22" height="22" fill="none" stroke="#ffffff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <span class="blue-stat-number" data-counter="{{ $successRate ?? 10 }}" data-suffix="%">0</span>
                            <span class="blue-stat-title">Tingkat Keberhasilan</span>
                            <span class="blue-stat-desc">Pengguna terserap kerja</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Job Listings -->
        @if(isset($jobs) && $jobs->count() > 0)
        <section class="jobs-section" id="jobs" x-data="{ jobsLoaded: true }">
            <div class="container">
                <div class="jobs-header">
                    <div>
                        <h2 class="section-title section-title--tight">Peluang Lowongan Terbaru</h2>
                        <p class="section-subtitle section-subtitle--tight">Temukan kesempatan karir terbaik untuk Anda</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="jobs-header-link">
                        Lihat Semua Lowongan
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <div class="jobs-carousel-wrap">
                    <button class="carousel-btn carousel-btn-prev" aria-label="Previous">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="job-carousel-track" x-show="jobsLoaded">
                        @foreach($jobs->take(4) as $index => $job)
                        @php
                            $colors = ['#2563eb', '#15803d', '#ef4444', '#eab308'];
                            $bgColor = $colors[$index % 4];
                        @endphp
                        <article class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-company">
                                    <div class="job-card-logo" style="background:{{ $bgColor }};">{{ strtoupper(substr($job->company_name ?? 'B', 0, 1)) }}</div>
                                    <div>
                                        <h3 class="job-card-title">{{ $job->title }}</h3>
                                        <p class="job-card-company-name">{{ $job->company_name ?? 'Perusahaan Mitra' }}</p>
                                    </div>
                                </div>
                                <button type="button" class="job-card-bookmark" aria-label="Bookmark">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                                </button>
                            </div>
                            <div class="job-card-tags">
                                <span class="job-tag">{{ $job->location }}</span>
                                <span class="job-tag">{{ \App\Support\Label::jobType($job->job_type) ?? 'Penuh Waktu' }}</span>
                            </div>
                            <div class="job-card-salary-wrap">
                                <p class="job-card-salary">{{ $job->salary ? 'Rp ' . number_format($job->salary, 0, ',', '.') : 'Negosiasi' }}</p>
                                <p class="job-card-deadline">Deadline: {{ \Carbon\Carbon::parse($job->deadline)->translatedFormat('j M Y') }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $job->id) }}" class="job-card-link">
                                Lihat Detail
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </article>
                        @endforeach
                    </div>
                    <button class="carousel-btn carousel-btn-next" aria-label="Next">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <div class="jobs-cta">
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        Lihat Semua Lowongan
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </section>
        @else
        <section class="jobs-section" id="jobs">
            <div class="container">
                <div class="jobs-header">
                    <div>
                        <h2 class="section-title section-title--tight">Peluang Lowongan Terbaru</h2>
                        <p class="section-subtitle section-subtitle--tight">Temukan kesempatan karir terbaik untuk Anda</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="jobs-header-link">
                        Lihat Semua Lowongan
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <div class="jobs-carousel-wrap">
                    <button class="carousel-btn carousel-btn-prev" aria-label="Previous">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="job-carousel-track">
                        <!-- Sample Card 1 -->
                        <article class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-company">
                                    <div class="job-card-logo" style="background:#2563eb;">M</div>
                                    <div>
                                        <h3 class="job-card-title">Operator Produksi</h3>
                                        <p class="job-card-company-name">PT Maju Bersama</p>
                                    </div>
                                </div>
                                <button type="button" class="job-card-bookmark" aria-label="Bookmark">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                                </button>
                            </div>
                            <div class="job-card-tags">
                                <span class="job-tag">Karawang Barat</span>
                                <span class="job-tag">Penuh Waktu</span>
                            </div>
                            <div class="job-card-salary-wrap">
                                <p class="job-card-salary">Rp 4.000.000 - 5.200.000</p>
                                <p class="job-card-deadline">Deadline: 31 Jul 2026</p>
                            </div>
                            <span class="job-card-link disabled" aria-disabled="true">Lihat Detail <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                        </article>
                        <!-- Sample Card 2 -->
                        <article class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-company">
                                    <div class="job-card-logo" style="background:#15803d;">T</div>
                                    <div>
                                        <h3 class="job-card-title">Junior Web Developer</h3>
                                        <p class="job-card-company-name">PT Teknologi Nusantara</p>
                                    </div>
                                </div>
                                <button type="button" class="job-card-bookmark" aria-label="Bookmark">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                                </button>
                            </div>
                            <div class="job-card-tags">
                                <span class="job-tag">Karawang Timur</span>
                                <span class="job-tag">Penuh Waktu</span>
                            </div>
                            <div class="job-card-salary-wrap">
                                <p class="job-card-salary">Rp 4.500.000 - 6.500.000</p>
                                <p class="job-card-deadline">Deadline: 28 Jul 2026</p>
                            </div>
                            <span class="job-card-link disabled" aria-disabled="true">Lihat Detail <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                        </article>
                        <!-- Sample Card 3 -->
                        <article class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-company">
                                    <div class="job-card-logo" style="background:#ef4444;">R</div>
                                    <div>
                                        <h3 class="job-card-title">Staf Administrasi</h3>
                                        <p class="job-card-company-name">PT Ritel Karawang</p>
                                    </div>
                                </div>
                                <button type="button" class="job-card-bookmark" aria-label="Bookmark">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                                </button>
                            </div>
                            <div class="job-card-tags">
                                <span class="job-tag">Karawang</span>
                                <span class="job-tag">Penuh Waktu</span>
                            </div>
                            <div class="job-card-salary-wrap">
                                <p class="job-card-salary">Rp 3.500.000 - 4.500.000</p>
                                <p class="job-card-deadline">Deadline: 25 Jul 2026</p>
                            </div>
                            <span class="job-card-link disabled" aria-disabled="true">Lihat Detail <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                        </article>
                        <!-- Sample Card 4 -->
                        <article class="job-card">
                            <div class="job-card-top">
                                <div class="job-card-company">
                                    <div class="job-card-logo" style="background:#eab308;">K</div>
                                    <div>
                                        <h3 class="job-card-title">Resepsionis Hotel</h3>
                                        <p class="job-card-company-name">PT Karawang Hospitality</p>
                                    </div>
                                </div>
                                <button type="button" class="job-card-bookmark" aria-label="Bookmark">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                                </button>
                            </div>
                            <div class="job-card-tags">
                                <span class="job-tag">Karawang</span>
                                <span class="job-tag">Penuh Waktu</span>
                            </div>
                            <div class="job-card-salary-wrap">
                                <p class="job-card-salary">Rp 3.800.000 - 4.800.000</p>
                                <p class="job-card-deadline">Deadline: 30 Jul 2026</p>
                            </div>
                            <span class="job-card-link disabled" aria-disabled="true">Lihat Detail <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></span>
                        </article>
                    </div>
                    <button class="carousel-btn carousel-btn-next" aria-label="Next">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <div class="jobs-cta">
                    <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                        Lihat Semua Lowongan
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </section>
        @endif

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-box">
                    <div class="cta-deco"></div>
                    <div class="cta-content">
                        <h2 class="cta-title">Siap Memulai Karier Anda?</h2>
                        <p class="cta-desc">Bergabunglah bersama para pencari kerja yang telah menemukan pekerjaan impian melalui BKK SMK MUTU.</p>
                        @php
                            $abTest = app(\App\Services\ABTestingService::class);
                            $ctaBanner = $abTest->getCtaCopy('cta_banner');
                            $ctaLabel = auth()->check() ? $ctaBanner['auth'] : $ctaBanner['guest'];
                            $ctaRoute = auth()->check() ? route('jobs.index') : route('register');
                            $ctaVariant = $abTest->getVariation('cta_banner');
                        @endphp
                        <a href="{{ $ctaRoute }}" class="btn btn-white"
                           data-ab-test="cta_banner"
                           data-ab-variant="{{ $ctaVariant }}"
                           @click="$dispatch('ab-test-track', { event: 'cta_banner_click', variant: '{{ $ctaVariant }}' })">
                            {{ $ctaLabel }}
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <div class="cta-visual">
                        <svg class="cta-chart-svg" width="280" height="220" viewBox="0 0 280 220" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="140" cy="110" r="100" fill="rgba(255,255,255,0.08)"/>
                            <path d="M30 190 L70 150 L110 130 L150 95 L190 75 L230 55 L270 40" stroke="rgba(255,255,255,0.5)" stroke-width="3" stroke-linecap="round" fill="none">
                                <animate attributeName="stroke-dasharray" from="0 600" to="600 0" dur="1.5s" fill="freeze" begin="0.3s"/>
                            </path>
                            <circle cx="70" cy="150" r="5" fill="#fff"><animate attributeName="r" from="0" to="5" dur="0.3s" fill="freeze" begin="0.6s"/></circle>
                            <circle cx="110" cy="130" r="5" fill="#fff"><animate attributeName="r" from="0" to="5" dur="0.3s" fill="freeze" begin="0.7s"/></circle>
                            <circle cx="150" cy="95" r="5" fill="#fff"><animate attributeName="r" from="0" to="5" dur="0.3s" fill="freeze" begin="0.8s"/></circle>
                            <circle cx="190" cy="75" r="5" fill="#fff"><animate attributeName="r" from="0" to="5" dur="0.3s" fill="freeze" begin="0.9s"/></circle>
                            <circle cx="230" cy="55" r="5" fill="#fff"><animate attributeName="r" from="0" to="5" dur="0.3s" fill="freeze" begin="1s"/></circle>
                            <circle cx="270" cy="40" r="6" fill="#fff"><animate attributeName="r" from="0" to="6" dur="0.4s" fill="freeze" begin="1.1s"/></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-grid">
                <!-- Brand -->
                <div>
                    <div class="footer-brand-name">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" width="40" height="40">
                        <span>BKK SMK MUTU</span>
                    </div>
                    <p class="footer-brand-desc">Platform karir untuk para pencari kerja.<br>Menghubungkan talenta muda dengan perusahaan terpercaya.</p>
                    <div class="footer-socials">
                        <a href="javascript:void(0)" class="footer-social-link" aria-label="Instagram">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="javascript:void(0)" class="footer-social-link" aria-label="Facebook">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="javascript:void(0)" class="footer-social-link" aria-label="LinkedIn">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="javascript:void(0)" class="footer-social-link" aria-label="YouTube">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="footer-col-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('jobs.index') }}">Lowongan</a></li>
                        <li><a href="javascript:void(0)">Perusahaan</a></li>
                        <li><a href="javascript:void(0)">Artikel</a></li>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                    </ul>
                </div>
                <!-- Job Seeker -->
                <div>
                    <h4 class="footer-col-title">Job Seeker</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('jobs.index') }}">Cari Lowongan</a></li>
                        <li><a href="javascript:void(0)">Tips Karir</a></li>
                    </ul>
                </div>
                <!-- Contact -->
                <div>
                    <h4 class="footer-col-title">Contact</h4>
                    <ul class="footer-links">
                        <li>SMK MUTU Cikampek</li>
                        <li>Cikampek, Jawa Barat</li>
                        <li><a href="mailto:bkk@smkmutu.sch.id">bkk@smkmutu.sch.id</a></li>
                        <li><a href="tel:+62267123456">(0267) 123-456</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} BKK SMK MUTU. Hak cipta dilindungi.</p>
                <div class="footer-bottom-links">
                    <a href="javascript:void(0)">Privacy Policy</a>
                    <a href="javascript:void(0)">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Navbar scroll effect
            var navbar = document.getElementById('navbar');
            if (navbar) {
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 10) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }

            // Time-based greeting
            var greetingEl = document.getElementById('js-hero-greeting');
            if (greetingEl) {
                var hour = new Date().getHours();
                var greet = 'Selamat Datang di BKK SMK MUTU';
                var emoji = '\u{1F44B}';
                if (hour >= 4 && hour < 11) { greet = 'Selamat Pagi di BKK SMK MUTU'; emoji = '\u{1F305}'; }
                else if (hour >= 11 && hour < 15) { greet = 'Selamat Siang di BKK SMK MUTU'; emoji = '\u2600\u{FE0F}'; }
                else if (hour >= 15 && hour < 18) { greet = 'Selamat Sore di BKK SMK MUTU'; emoji = '\u{1F324}\u{FE0F}'; }
                else { greet = 'Selamat Malam di BKK SMK MUTU'; emoji = '\u{1F319}'; }
                greetingEl.innerHTML = '<span aria-hidden="true">' + emoji + '</span> ' + greet;
                window.requestAnimationFrame(function () { greetingEl.classList.add('is-visible'); });
            }

            // Scroll reveal
            var revealItems = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window) {
                var obs = new IntersectionObserver(function (entries, observer) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2 });
                revealItems.forEach(function (item) { obs.observe(item); });
            } else {
                revealItems.forEach(function (item) { item.classList.add('is-visible'); });
            }

            // Counter animation
            var counters = document.querySelectorAll('[data-counter]');
            var cObs = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) { animateCounter(entry.target); observer.unobserve(entry.target); }
                });
            }, { threshold: 0.4 });
            counters.forEach(function (c) { cObs.observe(c); });

            function animateCounter(el) {
                var target = parseInt(String(el.dataset.counter || '0').replace(/\D/g, ''), 10) || 0;
                var suffix = el.dataset.suffix || '';
                var duration = Math.min(2200, Math.max(900, 900 + Math.log10(target + 1) * 500));
                var start = null;
                function step(ts) {
                    if (!start) start = ts;
                    var p = Math.min((ts - start) / duration, 1);
                    el.textContent = Math.floor(p * target).toLocaleString('id-ID') + suffix;
                    if (p < 1) requestAnimationFrame(step); else el.textContent = target.toLocaleString('id-ID') + suffix;
                }
                requestAnimationFrame(step);
            }

            // Carousel
            document.querySelectorAll('.jobs-carousel-wrap').forEach(function(wrapper) {
                var track = wrapper.querySelector('.job-carousel-track');
                var prev = wrapper.querySelector('.carousel-btn-prev');
                var next = wrapper.querySelector('.carousel-btn-next');
                if (track && prev && next) {
                    prev.addEventListener('click', function() { track.scrollBy({ left: -300, behavior: 'smooth' }); });
                    next.addEventListener('click', function() { track.scrollBy({ left: 300, behavior: 'smooth' }); });
                }
            });
        });
    </script>
</body>
</html>
