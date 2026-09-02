<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'BKK SMK MUTU'))</title>
    <meta name="description" content="@yield('description', 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', config('app.name', 'BKK SMK MUTU'))">
    <meta property="og:description" content="@yield('description', 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">

</head>
<body class="font-sans antialiased">

    <div class="auth-wrap">

        {{-- Panel Kiri — Sidebar --}}
        <aside class="auth-sidebar">
            <div class="auth-sidebar-decor"></div>
            <div class="auth-sidebar-decor-2"></div>

            {{-- Header --}}
            <div class="auth-sidebar-header">
                <div class="auth-sidebar-logo">
                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU">
                    <div>
                        <div class="auth-sidebar-brand">BKK SMK MUTU</div>
                        <div class="auth-sidebar-subbrand">Pusat Pengembangan Karir</div>
                    </div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="auth-sidebar-nav">
                <div class="auth-nav-section">
                    <h3 class="auth-nav-section-title">Fitur Utama</h3>

                    <a href="{{ route('jobs.index') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Cari Lowongan</span>
                        <span class="auth-nav-badge">Live</span>
                    </a>

                    <a href="{{ route('register') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>Daftar Akun</span>
                        <span class="auth-nav-badge">Gratis</span>
                    </a>

                    <a href="{{ route('cv.builder') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Pembuat CV</span>
                        <span class="auth-nav-badge">ATS</span>
                    </a>
                </div>

                <div class="auth-nav-section" style="margin-top: 1.5rem;">
                    <h3 class="auth-nav-section-title">Layanan</h3>

                    <a href="{{ route('certificates.index') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span>Sertifikat</span>
                    </a>

                    <a href="{{ route('events.index') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Acara & Pelatihan</span>
                    </a>

                    <a href="{{ route('news.index') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>Berita Karir</span>
                    </a>
                </div>

                <div class="auth-nav-section" style="margin-top: 1.5rem;">
                    <h3 class="auth-nav-section-title">Untuk Perusahaan</h3>

                    <a href="{{ route('company.jobs.index') }}" class="auth-nav-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Rekrut Karyawan</span>
                    </a>
                </div>
            </nav>

            {{-- Stats --}}
            <div class="auth-sidebar-stats">
                <div class="auth-sidebar-stats-inner">
                    <div class="auth-sidebar-stats-title">Angka Kami</div>
                    <div class="auth-stats-grid">
                        <div class="auth-stat-item">
                            <div class="auth-stat-num">{{ number_format(\App\Models\User::where('role', 'umum')->count()) }}</div>
                            <div class="auth-stat-label">Pencari Kerja</div>
                        </div>
                        <div class="auth-stat-item">
                            <div class="auth-stat-num">{{ number_format(\App\Models\Job::where('status', 'active')->where('deadline', '>=', now())->count()) }}</div>
                            <div class="auth-stat-label">Lowongan Aktif</div>
                        </div>
                        <div class="auth-stat-item">
                            <div class="auth-stat-num">{{ number_format(\App\Models\Company::count()) }}</div>
                            <div class="auth-stat-label">Perusahaan</div>
                        </div>
                        <div class="auth-stat-item">
                            <div class="auth-stat-num">{{ number_format(\App\Models\Application::count()) }}</div>
                            <div class="auth-stat-label">Lamaran Tersimpan</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="auth-sidebar-footer">
                <div class="auth-sidebar-contact">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>bkk@smkmutu.sch.id</span>
                </div>
            </div>
        </aside>

        {{-- Panel Kanan --}}
        <div class="auth-right">

            {{-- Logo mobile --}}
            <div class="auth-mobile-logo">
                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU">
                <div>
                    <span class="auth-mobile-title">BKK SMK MUTU</span>
                    <span class="auth-mobile-subtitle">Pusat Pengembangan Karir</span>
                </div>
            </div>

            {{-- Card --}}
            <div class="auth-card-wrapper">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>

            {{-- Back link --}}
            <div class="auth-back">
                <a href="{{ route('home') }}">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-8 h-8 rounded-lg object-cover">
                        <div>
                            <h3 class="text-lg font-semibold text-white">BKK SMK MUTU</h3>
                            <p class="text-xs text-gray-400">Platform karir pencari kerja SMK</p>
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
                    <a href="javascript:void(0);" role="button" aria-disabled="true" class="transition hover:text-white opacity-70 cursor-default">Kebijakan Privasi</a>
                    <a href="javascript:void(0);" role="button" aria-disabled="true" class="transition hover:text-white opacity-70 cursor-default">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
