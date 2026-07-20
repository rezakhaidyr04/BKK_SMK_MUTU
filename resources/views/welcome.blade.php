<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BKK SMK MUTU - Platform Pengembangan Karir</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Local Tailwind CSS (no internet required) --}}
    <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-custom.css') }}">
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
                
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Beranda</a>
                    <a href="#jobs" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Lowongan</a>
                    <a href="#trusted" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Perusahaan</a>
                    <a href="#features" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Tentang Kami</a>
                    <a href="{{ route('news.index') }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition">Blog</a>
                </div>
                <div class="flex items-center gap-4">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                            Dasbor
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="ui-btn ui-btn-secondary ui-btn-sm">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="ui-btn ui-btn-primary ui-btn-sm">
                            Daftar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main>
    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden pt-24 pb-20">
        <div class="hero-bg absolute inset-0 -z-10"></div>
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <span class="hero-circle hero-circle-1"></span>
            <span class="hero-circle hero-circle-2"></span>
            <span class="hero-circle hero-circle-3"></span>
            <span class="hero-particle hero-particle-1"></span>
            <span class="hero-particle hero-particle-2"></span>
            <span class="hero-particle hero-particle-3"></span>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 items-center">
                <div class="space-y-8">
                    <div id="js-hero-greeting" class="hero-greeting inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/15 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-950/20 backdrop-blur-xl">
                        👋 Selamat Datang di BKK SMK MUTU
                    </div>
                    <div class="space-y-6">
                        <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl lg:text-6xl leading-tight">
                            Temukan Karir Impian<br class="hidden xl:inline" />Anda bersama<br class="hidden xl:inline" />BKK SMK MUTU
                        </h1>
                        <p class="max-w-2xl text-base text-slate-100 sm:text-lg lg:text-xl">
                            Platform Bursa Kerja Khusus modern yang menghubungkan siswa dan alumni dengan perusahaan terbaik untuk membangun karier masa depan.
                        </p>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <a href="{{ route('register') }}" class="ui-btn ui-btn-primary ui-btn-lg" aria-label="Mulai Gratis">
                            Mulai Gratis
                        </a>
                        <a href="{{ route('jobs.index') }}" class="ui-btn ui-btn-white ui-btn-lg" aria-label="Lihat Lowongan">
                            Lihat Lowongan
                        </a>
                    </div>

                    <aside class="hero-info-bar mt-8 rounded-full border border-white/15 bg-white/15 px-3 py-3 shadow-[0_20px_50px_-30px_rgba(15,23,42,0.8)] backdrop-blur-xl" aria-label="Info ringkas BKK SMK MUTU">
                        <div class="overflow-hidden">
                            <div class="hero-info-scroll flex items-center gap-8 whitespace-nowrap">
                                <span class="hero-info-pill">🔥 15 Lowongan Baru Hari Ini</span>
                                <span class="hero-info-pill">📅 Job Fair 12 Agustus 2026</span>
                                <span class="hero-info-pill">⭐ 120+ Alumni Diterima Bulan Ini</span>
                                <span class="hero-info-pill">📄 CV Builder Kini Tersedia</span>
                                <span class="hero-info-pill">🔥 15 Lowongan Baru Hari Ini</span>
                                <span class="hero-info-pill">📅 Job Fair 12 Agustus 2026</span>
                                <span class="hero-info-pill">⭐ 120+ Alumni Diterima Bulan Ini</span>
                                <span class="hero-info-pill">📄 CV Builder Kini Tersedia</span>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative isolate w-full max-w-xl">
                        <div class="absolute -left-8 top-8 h-36 w-36 rounded-full bg-sky-400/20 blur-3xl"></div>
                        <div class="absolute -right-10 top-1/2 h-24 w-24 rounded-full bg-cyan-300/15 blur-3xl"></div>
                        <div class="absolute -bottom-12 left-16 h-44 w-44 rounded-full bg-blue-500/10 blur-3xl"></div>
                        <div class="hero-illustration-card relative overflow-hidden rounded-[2rem] border border-white/15 bg-white/10 p-6 shadow-2xl shadow-slate-950/20 backdrop-blur-xl">
                            <div class="absolute inset-x-0 top-0 h-32 bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.28),_transparent_65%)]"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white/90">BKK SMK MUTU</span>
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white/80">★</span>
                                </div>
                                <div class="relative mx-auto mb-6 h-[340px] w-full overflow-hidden rounded-[2rem] bg-slate-950/90 border border-white/10 shadow-inner">
                                    <img src="{{ asset('images/foto_siswa/siswa.png') }}" alt="Ilustrasi siswa BKK SMK MUTU" class="h-full w-full object-cover" />
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-white/85 text-sm">
                                    <div class="rounded-2xl bg-white/6 p-4">
                                        <span class="block text-xs uppercase tracking-[0.24em] text-slate-300">Persiapan CV</span>
                                        <p class="mt-2 font-semibold text-base text-white">Tampilan profesional</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/6 p-4">
                                        <span class="block text-xs uppercase tracking-[0.24em] text-slate-300">Interview</span>
                                        <p class="mt-2 font-semibold text-base text-white">90% alumni dipandu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Companies -->
    <section id="trusted" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-sm uppercase tracking-[0.32em] text-slate-400">Dipercaya oleh Perusahaan Terkemuka</p>
                <h2 class="mt-4 text-3xl font-semibold text-slate-900 sm:text-4xl">Jaringan mitra yang terus berkembang</h2>
            </div>
            <div class="logo-marquee-wrapper overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                <div class="logo-marquee-track flex items-center gap-6 px-6 py-6">
                    <img src="{{ asset('images/companies/Pt.Astra.png') }}" alt="PT Astra" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/Samsung.png') }}" alt="PT Samsung" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/Pt_pupuk_kujang.png') }}" alt="PT Pupuk Kujang" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/toyota.png') }}" alt="PT Toyota" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/telkom.png') }}" alt="PT Telkom" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/Alice_international.png') }}" alt="PT Alice" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/honda.png') }}" alt="PT Denso" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/diametral.png') }}" alt="PT Diametral" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/bank_mega_syariah.png') }}" alt="PT Bank Mega Syariah" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/indibiz.png') }}" alt="PT Indibiz" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/cucas.png') }}" alt="Cucas" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/Kidi_iot_antares_indonesia.png') }}" alt="PT Kidi Iot Antares_indonesia" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/chemco.png') }}" alt="PT Chemco" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/lpk_ori.png') }}" alt="Lpk Ori Ryousen" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/bansu.png') }}" alt="PT Banshu Bumber" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                    <img src="{{ asset('images/companies/astra.png') }}" alt="PT Astra" class="h-10 w-auto rounded-2xl bg-slate-100 p-3" />
                </div>
                </div>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose BKK -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-sm uppercase tracking-[0.32em] text-slate-400">Nilai unggul platform</p>
                <h2 class="mt-4 text-3xl font-semibold text-slate-900 sm:text-4xl">Mengapa Pilih BKK SMK MUTU?</h2>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-500/10 text-blue-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v5h6v-5c0-1.657-1.343-3-3-3zM6 20h12" /></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Smart Job Matching</h3>
                    <p class="text-slate-600">Rekomendasi karir berdasarkan profil kompetensi dan minat Anda.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl" style="animation-delay: 100ms;">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-500/10 text-sky-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h7a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h5m1-3v4m0 0L9 9m3-4l3 3"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">CV ATS Friendly</h3>
                    <p class="text-slate-600">Format CV yang mudah terbaca dan diterima oleh sistem perekrutan perusahaan.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl" style="animation-delay: 200ms;">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-emerald-500/10 text-emerald-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Application Tracking</h3>
                    <p class="text-slate-600">Pantau status lamaran secara realtime langsung dari dashboard Anda.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl" style="animation-delay: 300ms;">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-indigo-500/10 text-indigo-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l9-5-9-5-9 5 9 5z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">AI Recommendation</h3>
                    <p class="text-slate-600">Saran pekerjaan pintar berdasarkan riwayat pencarian dan kemampuan Anda.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl" style="animation-delay: 400ms;">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-cyan-500/10 text-cyan-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Trusted Companies</h3>
                    <p class="text-slate-600">Jaringan perusahaan terpercaya yang sering merekrut lulusan SMK MUTU.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-slate-50 p-8 shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl" style="animation-delay: 500ms;">
                    <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-slate-800/10 text-slate-900">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.105.895-2 2-2h5v9a1 1 0 01-1 1h-9a1 1 0 01-1-1v-2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h.01M6 11h.01M6 15h.01M10 7h.01M10 11h.01M10 15h.01"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Secure Data</h3>
                    <p class="text-slate-600">Keamanan data pribadi dan riwayat lamaran selalu menjadi prioritas.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-sm uppercase tracking-[0.32em] text-slate-400">Langkah demi langkah</p>
                <h2 class="mt-4 text-3xl font-semibold text-slate-900 sm:text-4xl">Bagaimana Cara Kerjanya?</h2>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-xl" data-reveal>
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600 text-xl font-bold">1</div>
                    <h3 class="mt-6 text-xl font-semibold text-slate-900">Daftar Akun</h3>
                    <p class="mt-3 text-sm text-slate-500">Buat akun gratis dan masuk ke platform.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-xl" data-reveal>
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 text-xl font-bold">2</div>
                    <h3 class="mt-6 text-xl font-semibold text-slate-900">Lengkapi Profil</h3>
                    <p class="mt-3 text-sm text-slate-500">Isi data diri, pendidikan, dan keahlian Anda.</p>
                </article>
                <article class="fade-reveal rounded-[1.75rem] border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-xl" data-reveal>
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-cyan-100 text-cyan-600 text-xl font-bold">3</div>
                    <h3 class="mt-6 text-xl font-semibold text-slate-900">Cari dan Lamar</h3>
                    <p class="mt-3 text-sm text-slate-500">Temukan lowongan terbaik dan ajukan lamaran dengan mudah.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-20 bg-gradient-to-br from-sky-600 via-blue-700 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-sm uppercase tracking-[0.32em] text-cyan-200/70">Angka yang berbicara</p>
                <h2 class="mt-4 text-3xl font-semibold sm:text-4xl">Statistik Keberhasilan Kami</h2>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl bg-white/10 p-8 text-center backdrop-blur-xl shadow-lg shadow-slate-950/20">
                    <p class="text-5xl font-semibold leading-none" data-counter="150">0</p>
                    <p class="mt-4 text-sm uppercase tracking-[0.24em] text-cyan-200/80">Perusahaan Mitra</p>
                </div>
                <div class="rounded-3xl bg-white/10 p-8 text-center backdrop-blur-xl shadow-lg shadow-slate-950/20">
                    <p class="text-5xl font-semibold leading-none" data-counter="2500">0</p>
                    <p class="mt-4 text-sm uppercase tracking-[0.24em] text-cyan-200/80">Alumni</p>
                </div>
                <div class="rounded-3xl bg-white/10 p-8 text-center backdrop-blur-xl shadow-lg shadow-slate-950/20">
                    <p class="text-5xl font-semibold leading-none" data-counter="5000">0</p>
                    <p class="mt-4 text-sm uppercase tracking-[0.24em] text-cyan-200/80">Lowongan</p>
                </div>
                <div class="rounded-3xl bg-white/10 p-8 text-center backdrop-blur-xl shadow-lg shadow-slate-950/20">
                    <p class="text-5xl font-semibold leading-none" data-counter="93" data-suffix="%">0</p>
                    <p class="mt-4 text-sm uppercase tracking-[0.24em] text-cyan-200/80">Berhasil Bekerja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Jobs Section -->
    @if(isset($jobs) && $jobs->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Peluang Lowongan Terbaru</h2>
                <p class="text-xl text-gray-600">Lowongan pilihan yang cocok dengan kemampuan anda.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($jobs->take(6) as $job)
                <article class="group rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-blue-50 text-blue-600 font-semibold text-xl">{{ strtoupper(substr($job->company->name ?? 'BKK', 0, 2)) }}</div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $job->title }}</h3>
                                <p class="text-sm text-slate-500">{{ $job->company->name ?? 'Perusahaan Mitra' }}</p>
                            </div>
                        </div>
                        <button type="button" class="text-slate-400 transition hover:text-blue-600" aria-label="Bookmark lowongan {{ $job->title }}">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v16l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                        </button>
                    </div>
                    <div class="mt-6 grid gap-3 text-sm text-slate-500 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <span class="block text-xs text-slate-400">Gaji</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ $job->salary ? 'Rp ' . number_format($job->salary, 0, ',', '.') : 'Negosiasi' }}</span>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <span class="block text-xs text-slate-400">Location</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ $job->location }}</span>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <span class="block text-xs text-slate-400">Tipe</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ \App\Support\Label::jobType($job->job_type) ?? 'Penuh Waktu' }}</span>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <span class="block text-xs text-slate-400">Deadline</span>
                            <span class="mt-2 block font-semibold text-slate-900">{{ \Carbon\Carbon::parse($job->deadline)->translatedFormat('j M Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between gap-4">
                        <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">#{{ \Illuminate\Support\Str::slug($job->job_type ?? 'job', '-') }}</span>
                        <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 font-semibold transition hover:text-blue-700">Detail</a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Peluang Lowongan Terbaru</h2>
            </div>
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada lowongan tersedia saat ini</h3>
                <p class="text-gray-500 text-sm max-w-sm">Pantau terus platform kami. Lowongan kerja baru akan segera hadir dari perusahaan-perusahaan mitra kami.</p>
            </div>
        </div>
    </section>
    @endif

    <!-- Call To Action -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-blue-700 via-sky-600 to-cyan-500 px-6 py-14 shadow-2xl shadow-slate-950/15">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.18),_transparent_32%)]"></div>
                <div class="absolute right-0 top-1/2 h-72 w-72 -translate-y-1/2 rounded-full bg-white/10 blur-3xl"></div>
                <div class="relative grid gap-10 lg:grid-cols-2 items-center">
                    <div class="space-y-6 text-white">
                        <p class="text-sm uppercase tracking-[0.32em] text-cyan-100/80">Ayo mulai</p>
                        <h2 class="text-4xl font-semibold sm:text-5xl">Siap Memulai Karier Anda?</h2>
                        <p class="max-w-xl text-base text-slate-100/90">Bergabung bersama ribuan alumni yang telah menemukan pekerjaan impian melalui BKK SMK MUTU.</p>
                        @guest
                        <a href="{{ route('register') }}" class="inline-flex rounded-full bg-white px-8 py-4 text-base font-semibold text-blue-700 shadow-lg shadow-slate-950/15 transition hover:bg-slate-50">Daftar Sekarang Gratis</a>
                        @else
                        <a href="{{ route('jobs.index') }}" class="inline-flex rounded-full bg-white px-8 py-4 text-base font-semibold text-blue-700 shadow-lg shadow-slate-950/15 transition hover:bg-slate-50">Lihat Lowongan</a>
                        @endguest
                    </div>
                    <div class="relative hidden lg:block">
                        <div class="absolute -left-16 top-10 h-32 w-32 rounded-full bg-white/15 blur-3xl"></div>
                        <div class="relative mx-auto h-[360px] w-full max-w-lg overflow-hidden rounded-[2rem] bg-slate-950/70 p-6 shadow-2xl">
                            <svg class="h-full w-full" viewBox="0 0 680 520" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <defs>
                                    <linearGradient id="cta-grad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.88" />
                                        <stop offset="100%" stop-color="#c7d2fe" stop-opacity="0.16" />
                                    </linearGradient>
                                </defs>
                                <rect x="28" y="38" width="624" height="432" rx="48" fill="url(#cta-grad)" opacity="0.1" />
                                <circle cx="142" cy="120" r="58" fill="#ffffff" opacity="0.08" />
                                <circle cx="560" cy="92" r="44" fill="#ffffff" opacity="0.08" />
                                <path d="M168 408c-26 0-48-21-48-47 0-26 22-47 48-47h38l18 36 18-36h38c26 0 48 21 48 47 0 26-22 47-48 47H168Z" fill="#fff" opacity="0.16" />
                                <path d="M436 386c-24 0-44-19-44-42 0-23 20-42 44-42h32l16 34 16-34h32c24 0 44 19 44 42 0 23-20 42-44 42H436Z" fill="#fff" opacity="0.16" />
                                <path d="M160 194c0-28 22-50 50-50h2c28 0 50 22 50 50v100c0 28-22 50-50 50h-2c-28 0-50-22-50-50V194Z" fill="#c7d2fe" />
                                <path d="M438 192c0-26 22-48 48-48h2c26 0 48 22 48 48v102c0 26-22 48-48 48h-2c-26 0-48-22-48-48V192Z" fill="#93c5fd" />
                                <path d="M208 246c18 0 34-14 34-32v-6c0-18-16-32-34-32s-34 14-34 32v6c0 18 16 32 34 32Z" fill="#fff" />
                                <path d="M496 252c16 0 30-12 30-28v-4c0-16-14-28-30-28s-30 12-30 28v4c0 16 14 28 30 28Z" fill="#fff" />
                                <path d="M216 336c0 26-18 46-40 46s-40-20-40-46c0-26 18-46 40-46s40 20 40 46Z" fill="#e2e8f0" />
                                <path d="M512 342c0 24-18 42-42 42s-42-18-42-42c0-24 18-42 42-42s42 18 42 42Z" fill="#c7d2fe" />
                                <path d="M138 312c6 0 10-4 10-10v-12c0-6-4-10-10-10s-10 4-10 10v12c0 6 4 10 10 10Z" fill="#ffffff" opacity="0.6" />
                                <path d="M372 314c8 0 12-6 12-14v-16c0-8-4-14-12-14s-12 6-12 14v16c0 8 4 14 12 14Z" fill="#ffffff" opacity="0.6" />
                            </svg>
                        </div>
                    </div>
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
                var target = parseInt(element.dataset.counter, 10) || 0;
                var suffix = element.dataset.suffix || '';
                var duration = 1600;
                var startTime = null;
                var initial = 0;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;
                    var progress = Math.min((timestamp - startTime) / duration, 1);
                    var current = Math.floor(progress * (target - initial) + initial);
                    element.textContent = current + suffix;
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        element.textContent = target + suffix;
                    }
                }
                window.requestAnimationFrame(step);
            }
        });
    </script>
</body>
</html>
