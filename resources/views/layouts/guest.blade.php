<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logos/mutu_logo.png') }}">
    <title>@yield('title', config('app.name', 'BKKMu'))</title>
    <meta name="description" content="@yield('description', 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', config('app.name', 'BKKMu'))">
    <meta property="og:description" content="@yield('description', 'Bursa Kerja Khusus (BKK) SMK MUTU — informasi lowongan kerja, pelatihan, dan pendampingan karier bagi siswa dan alumni.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- ?v= bertambah setiap app-custom.css diubah agar browser tidak memakai cache lama --}}
        <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}?v=20261007a">

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
                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKKMu">
                    <div>
                        <div class="auth-sidebar-brand">BKKMu</div>
                        <div class="auth-sidebar-subbrand">Pusat Pengembangan Karir</div>
                    </div>
                </div>
            </div>

            <div class="auth-sidebar-copy">
                <span class="auth-sidebar-tag">Platform Karier</span>
                <p>Memberikan akses informasi kerja, pelatihan, dan peluang masa depan yang lebih cerah untuk semua pengguna.</p>
            </div>

        </aside>

        {{-- Panel Kanan --}}
        <div class="auth-right">

            {{-- Logo mobile --}}
            <div class="auth-mobile-logo">
                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKKMu">
                <div>
                    <span class="auth-mobile-title">BKKMu</span>
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


</body>
</html>
