<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BKK SMK MUTU Karawang - Career Development Platform</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Local Tailwind CSS (no internet required) --}}
    <link rel="stylesheet" href="{{ asset('css/tailwind-local.css') }}">
    
    {{-- When Node.js is installed, replace above with: --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="BKK SMK MUTU" class="w-10 h-10 rounded-xl object-cover">
                        <span class="ml-3 text-xl font-bold text-gray-900">BKK SMK MUTU</span>
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
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors border border-gray-300 rounded-lg">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md">
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
                        Find Your Dream Career with BKK SMK MUTU
                    </h1>
                    <p class="text-xl text-blue-100 mb-8">
                        Platform karir modern yang menghubungkan siswa dan alumni SMK dengan peluang kerja terbaik.
                    </p>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('jobs.index') }}" class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                                Browse Jobs
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                                Get Started Free
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl hover:bg-white/20 transition-all border border-white/20">
                                Sign In
                            </a>
                        @endauth
                    </div>
                    @guest
                        <p class="mt-4 text-sm text-blue-100 max-w-xl">
                            Admin? Gunakan tombol "Sign In" di atas untuk masuk sebagai admin. Pengguna biasa hanya perlu Daftar atau Browse Jobs.
                        </p>
                    @endguest
                    
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
                        <div class="w-full h-96 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 flex items-center justify-center">
                            <svg class="w-48 h-48 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose BKK SMK MUTU?</h2>
                <p class="text-xl text-gray-600">Platform terlengkap untuk pengembangan karir Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Job Matching</h3>
                    <p class="text-gray-600">Sistem rekomendasi pekerjaan berdasarkan skills dan minat Anda</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">ATS-Friendly CV</h3>
                    <p class="text-gray-600">Buat CV profesional yang optimize untuk Applicant Tracking System</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Application Tracking</h3>
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
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest Job Opportunities</h2>
                <p class="text-xl text-gray-600">Peluang karir terbaru untuk Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($jobs->take(6) as $job)
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-lg transition-all">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $job->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $job->company->name ?? 'Company' }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                            {{ $job->location }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                        </span>
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                        View Details →
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="text-center">
                <a href="{{ route('jobs.index') }}" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    View All Jobs
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Your Career Journey?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Bergabung dengan ribuan siswa dan alumni yang sudah menemukan pekerjaan impian mereka
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                    Get Started Free
                </a>
            @else
                <a href="{{ route('jobs.index') }}" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl">
                    Browse All Jobs
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
                            <p class="text-sm text-gray-400">Career Development Center</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Platform karir modern yang menghubungkan siswa dan alumni SMK dengan peluang kerja terbaik.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('jobs.index') }}" class="hover:text-white">Browse Jobs</a></li>
                        <li><a href="{{ route('events.index') }}" class="hover:text-white">Events</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white">Career News</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>SMK MUTU Karawang</li>
                        <li>Karawang, Jawa Barat</li>
                        <li>bkk@smkmutu.sch.id</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} BKK SMK MUTU KARAWANG. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
