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

    <!-- Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        Temukan Karir Impian Anda bersama BKK SMK MUTU
                    </h1>
                    <p class="text-xl text-blue-100 mb-8">
                        Platform karir modern yang menghubungkan siswa dan alumni SMK dengan peluang kerja terbaik.
                    </p>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('jobs.index') }}" class="ui-btn ui-btn-white ui-btn-lg">
                                Jelajahi Lowongan
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="ui-btn ui-btn-white ui-btn-lg">
                                Mulai Gratis
                            </a>
                            <a href="{{ route('login') }}" class="ui-btn ui-btn-lg" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);">
                                Masuk
                            </a>
                        @endauth
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div>
                            <div class="text-3xl font-bold">{{ number_format($activeJobsCount) }}</div>
                            <div class="text-blue-200 text-sm">Lowongan Aktif</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">{{ number_format($studentsCount) }}</div>
                            <div class="text-blue-200 text-sm">Siswa & Alumni</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">{{ number_format($companiesCount) }}</div>
                            <div class="text-blue-200 text-sm">Perusahaan</div>
                        </div>
                    </div>
                </div>
                
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute -top-6 -left-6 w-28 h-28 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-cyan-300/20 rounded-full blur-2xl"></div>
                        <div class="relative w-full h-96 rounded-3xl border border-white/20 bg-white/10 backdrop-blur-sm p-6 shadow-2xl overflow-hidden">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Dashboard ringkas</p>
                                    <h3 class="text-2xl font-bold mt-1">Alur kerja yang jelas</h3>
                                </div>
                                <div class="px-3 py-1 rounded-full bg-white/20 text-xs font-semibold border border-white/20">Live Preview</div>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl bg-white text-slate-900 p-4 shadow-lg">
                                        <p class="text-xs text-slate-500">Lowongan aktif</p>
                                        <p class="text-2xl font-bold mt-1">{{ number_format($activeJobsCount) }}</p>
                                        <p class="text-xs text-slate-500 mt-1">Siap dilihat</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/20 border border-white/15 p-4 text-white">
                                        <p class="text-xs text-white/70">Siswa & Alumni</p>
                                        <p class="text-2xl font-bold mt-1">{{ number_format($studentsCount) }}</p>
                                        <p class="text-xs text-white/70 mt-1">Mencari peluang</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/20 border border-white/15 p-4 text-white">
                                        <p class="text-xs text-white/70">Perusahaan</p>
                                        <p class="text-2xl font-bold mt-1">{{ number_format($companiesCount) }}</p>
                                        <p class="text-xs text-white/70 mt-1">Sedang membuka posisi</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-950/25 border border-white/10 p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-white">Langkah cepat</p>
                                            <p class="text-xs text-white/70">Cari, simpan, lalu lamar tanpa kebingungan.</p>
                                        </div>
                                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 text-xs font-semibold text-white/90">
                                        <div class="rounded-xl bg-white/10 border border-white/10 px-3 py-2">1. Cari lowongan</div>
                                        <div class="rounded-xl bg-white/10 border border-white/10 px-3 py-2">2. Simpan yang cocok</div>
                                        <div class="rounded-xl bg-white/10 border border-white/10 px-3 py-2">3. Lamar & pantau status</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-2xl bg-white/10 border border-white/15 p-4 text-white">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold">Lamaran terbaru</span>
                                            <span class="text-xs text-white/70">Realtime</span>
                                        </div>
                                        <div class="h-2 w-full rounded-full bg-white/20 overflow-hidden">
                                            <div class="h-full w-3/4 rounded-full bg-gradient-to-r from-cyan-300 to-emerald-300"></div>
                                        </div>
                                        <p class="text-xs text-white/70 mt-2">Status pengajuan bisa dipantau dari dasbor.</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/10 border border-white/15 p-4 text-white">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold">CV builder</span>
                                            <span class="text-xs text-white/70">ATS ready</span>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="h-2 rounded-full bg-white/20"></div>
                                            <div class="h-2 rounded-full bg-white/20 w-4/5"></div>
                                        </div>
                                        <p class="text-xs text-white/70 mt-2">Buat CV lebih rapi sebelum melamar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Flow Section -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-6 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold mb-4">1</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Cari lowongan yang relevan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Gunakan filter lokasi, jenis pekerjaan, dan kata kunci untuk menemukan peluang yang benar-benar cocok.</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-6 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold mb-4">2</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Simpan dan bandingkan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Bookmark lowongan yang menarik supaya kamu bisa membandingkan dan menilai sebelum melamar.</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-6 shadow-sm">
                    <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold mb-4">3</div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Lamar dan pantau status</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Kirim lamaran, cek statusnya, lalu ikuti update interview atau pesan dari perusahaan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih BKK SMK MUTU?</h2>
                <p class="text-xl text-gray-600">Platform terlengkap untuk pengembangan karir Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pencocokan Lowongan</h3>
                    <p class="text-gray-600">Sistem rekomendasi pekerjaan berdasarkan keahlian dan minat Anda</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">CV Ramah ATS</h3>
                    <p class="text-gray-600">Buat CV profesional yang dioptimalkan untuk Applicant Tracking System</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pelacakan Lamaran</h3>
                    <p class="text-gray-600">Monitor status lamaran Anda secara real-time dengan timeline</p>
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
                <p class="text-xl text-gray-600">Peluang karir terbaru untuk Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($jobs->take(6) as $job)
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-lg transition-all">
                    <div class="flex items-start gap-4 mb-3">
                        {{-- Logo Perusahaan --}}
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 flex items-center justify-center text-blue-600 font-bold text-lg">
                            {{ strtoupper(substr($job->company->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg text-gray-900 truncate">{{ $job->title }}</h3>
                            <p class="text-gray-600 text-sm">{{ $job->company->name ?? __('bkk.fallback.company') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                            {{ $job->location }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                            {{ \App\Support\Label::jobType($job->job_type) }}
                        </span>
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                        Lihat Detail &rarr;
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center">
                <a href="{{ route('jobs.index') }}" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Lihat Semua Lowongan
                </a>
            </div>
        </div>
    </section>
    @else
    {{-- Empty State: Belum ada lowongan --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Peluang Lowongan Terbaru</h2>
            </div>
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada lowongan tersedia saat ini</h3>
                <p class="text-gray-500 text-sm max-w-sm">Pantau terus platform kami. Lowongan kerja baru akan segera hadir dari perusahaan-perusahaan mitra kami.</p>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Siap Memulai Perjalanan Karir Anda?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Bergabung dengan ribuan siswa dan alumni yang sudah menemukan pekerjaan impian mereka
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                    Mulai Gratis
                </a>
            @else
                <a href="{{ route('jobs.index') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                    Jelajahi Semua Lowongan
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-12 h-12 rounded-xl object-cover">
                        <div>
                            <h3 class="text-lg font-bold">BKK SMK MUTU</h3>
                            <p class="text-sm text-gray-400">Pusat Pengembangan Karir</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Platform karir modern yang menghubungkan siswa dan alumni SMK dengan peluang kerja terbaik.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-white">Jelajahi Lowongan</a></li>
                        <li><a href="{{ route('events.index') }}" class="hover:text-white">Acara</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white">Berita Karir</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>SMK MUTU Cikampek</li>
                        <li>Cikampek, Jawa Barat</li>
                        <li>bkk@smkmutu.sch.id</li>
                        <li>
                            <a href="tel:+62267123456" class="hover:text-white">(0267) 123-456</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} BKK SMK MUTU. Hak cipta dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
