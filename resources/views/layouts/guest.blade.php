<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BKK SMK MUTU') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">

    <style>
        /* Layout utama */
        .auth-wrap {
            display: flex;
            min-height: 100vh;
        }

        /* Panel kiri — branding */
        .auth-left {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 42%;
            flex-shrink: 0;
            padding: 3rem;
            background-color: #1d4ed8; /* blue-700 */
            color: #fff;
        }
        @media (min-width: 1024px) {
            .auth-left { display: flex; }
        }

        .auth-left h2 {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 1.5rem 0 1rem;
            color: #fff;
        }
        .auth-left p {
            color: #bfdbfe; /* blue-200 */
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 280px;
        }
        .auth-logo-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }
        .auth-logo-row img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
        }
        .auth-logo-row .brand-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }
        .auth-logo-row .brand-sub {
            font-size: 0.8rem;
            color: #93c5fd; /* blue-300 */
        }
        .auth-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: auto;
        }
        .auth-stats .stat-num {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
        }
        .auth-stats .stat-label {
            font-size: 0.72rem;
            color: #93c5fd;
            margin-top: 2px;
        }

        /* Panel kanan — form */
        .auth-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6; /* gray-100 */
            padding: 2.5rem 1.5rem;
            overflow-y: auto;
        }

        /* Mobile logo */
        .auth-mobile-logo {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 2rem;
        }
        @media (min-width: 1024px) {
            .auth-mobile-logo { display: none; }
        }
        .auth-mobile-logo img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
        }
        .auth-mobile-logo span {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
        }

        /* Form card */
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.06);
            padding: 2rem;
        }

        /* Back link */
        .auth-back {
            margin-top: 1.5rem;
            text-align: center;
        }
        .auth-back a {
            font-size: 0.85rem;
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s;
        }
        .auth-back a:hover { color: #111827; }
    </style>
</head>
<body class="font-sans antialiased">

    <div class="auth-wrap">

        {{-- Panel Kiri --}}
        <div class="auth-left">
            <div>
                <div class="auth-logo-row">
                    <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU">
                    <div>
                        <div class="brand-name">BKK SMK MUTU</div>
                        <div class="brand-sub">Pusat Pengembangan Karir</div>
                    </div>
                </div>

                <h2>Menghubungkan<br>Talenta dengan<br>Peluang</h2>
                <p>Platform karir untuk siswa dan alumni SMK MUTU Cikampek menemukan pekerjaan terbaik mereka.</p>
            </div>

            <div class="auth-stats">
                <div class="text-center">
                    <div class="stat-num">{{ number_format(\App\Models\Company::count()) }}</div>
                    <div class="stat-label">Perusahaan Mitra</div>
                </div>
                <div class="text-center">
                    <div class="stat-num">{{ number_format(\App\Models\User::whereIn('role', ['student', 'alumni'])->count()) }}</div>
                    <div class="stat-label">Pengguna Terdaftar</div>
                </div>
                <div class="text-center">
                    <div class="stat-num">{{ number_format(\App\Models\Job::where('status', 'active')->where('deadline', '>=', now())->count()) }}</div>
                    <div class="stat-label">Lowongan Aktif</div>
                </div>
            </div>
        </div>

        {{-- Panel Kanan --}}
        <div class="auth-right">

            {{-- Logo mobile: tampil di layar kecil, tersembunyi di desktop --}}
            <div class="auth-mobile-logo">
                <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU">
                <div>
                    <span class="auth-mobile-title">BKK SMK MUTU</span>
                    <span class="auth-mobile-subtitle">Pusat Pengembangan Karir</span>
                </div>
            </div>

            {{-- Card --}}
            <div class="auth-card">
                {{ $slot }}
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

</body>
</html>
