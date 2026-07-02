<x-app-layout :full-bleed="true">
    <div class="page-shell">
        {{-- Hero Header (Reusable Component) --}}
        <x-ui.dashboard-hero
            title="Selamat datang kembali, {{ Auth::user()->name }}!"
            subtitle="Mari temukan pekerjaan impian Anda hari ini"
            gradient="from-blue-600 via-blue-700 to-indigo-700"
        >
            <x-slot:actions>
                {{-- Profile Completion Card --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 min-w-[240px]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white text-sm font-medium">Kelengkapan Profil</span>
                        <span class="text-white text-sm font-bold">{{ $stats['profile_completion'] }}%</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-2.5">
                        <div class="bg-green-400 h-2.5 rounded-full animate-progress-fill" style="width: {{ $stats['profile_completion'] }}%"></div>
                    </div>
                    @if($stats['profile_completion'] < 100)
                    <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-sm text-white hover:text-blue-100 underline">
                        Lengkapi profil Anda →
                    </a>
                    @endif
                </div>
            </x-slot:actions>
        </x-ui.dashboard-hero>

        <div class="page-container pt-6">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-blue-800">Fokus utama siswa dan alumni</p>
                        <p class="text-sm text-blue-700 mt-1">Cari lowongan, simpan yang cocok, lamar, lalu pantau status sampai selesai.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs font-semibold text-blue-700">
                        <span class="px-3 py-1 rounded-full bg-white border border-blue-100">Cari lowongan</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-blue-100">Kelola CV</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-blue-100">Pantau lamaran</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            {{-- Stats Grid (Refactored using reusable component) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <x-ui.dashboard-stat-card
                    label="Lamaran Aktif"
                    :value="$stats['active_applications']"
                    color="blue"
                    footnote="Sedang diproses"
                    class="animate-slide-up animate-slide-up-1"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Wawancara"
                    :value="$stats['interview_count']"
                    color="purple"
                    footnote="Terjadwal"
                    class="animate-slide-up animate-slide-up-2"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Diterima"
                    :value="$stats['accepted_count']"
                    color="green"
                    footnote="Berhasil diterima"
                    class="animate-slide-up animate-slide-up-3"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Disimpan"
                    :value="$stats['bookmarked_jobs']"
                    color="yellow"
                    footnote="Lowongan tersimpan"
                    class="animate-slide-up animate-slide-up-4"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Pesan"
                    :value="$stats['unread_messages']"
                    color="red"
                    footnote="Pesan belum dibaca"
                    class="animate-slide-up animate-slide-up-5"
                >
                    <x-slot:icon>
                        @if($stats['unread_messages'] > 0)
                        <div class="relative">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        </div>
                        @else
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @endif
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column (2/3) --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Job Recommendations Section --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rekomendasi Lowongan</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Disesuaikan dengan keahlian dan profil Anda</p>
                                    </div>
                                </div>
                                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1 group">
                                    Lihat Semua
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($recommendedJobs->count() > 0)
                                <div class="grid gap-4">
                                    @foreach($recommendedJobs->take(3) as $job)
                                    <div class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-750 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                @if($job->company->user->avatar ?? null)
                                                <img src="{{ asset('storage/' . $job->company->user->avatar) }}" alt="{{ $job->company->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-white dark:border-gray-700 shadow-md">
                                                @else
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md" aria-hidden="true">
                                                    {{ substr($job->company->name ?? 'C', 0, 1) }}
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1">
                                                    {{ $job->title }}
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $job->company->name ?? __('bkk.fallback.company') }}</p>
                                                
                                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $job->location }}
                                                    </span>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ \App\Support\Label::jobType($job->job_type) }}
                                                    </span>
                                                    @if($job->salary_min && $job->salary_max)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                                                    </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                        Lihat Detail
                                                    </a>
                                                    <button class="p-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" aria-label="Simpan Lowongan">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Match Score Badge -->
                                        <div class="absolute top-3 right-3">
                                            @php $score = $job->match_score ?? 0; @endphp
                                            @if($score >= 75)
                                                @php $badgeBg = 'bg-green-500'; $text = 'text-white'; @endphp
                                            @elseif($score >= 50)
                                                @php $badgeBg = 'bg-yellow-400'; $text = 'text-gray-900'; @endphp
                                            @else
                                                @php $badgeBg = 'bg-gray-200 dark:bg-gray-700'; $text = 'text-gray-700 dark:text-gray-300'; @endphp
                                            @endif
                                            <div class="px-3 py-1 {{ $badgeBg }} {{ $text }} text-xs font-bold rounded-full shadow-lg">
                                                {{ $score }}% Kecocokan
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada rekomendasi</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 max-w-sm mx-auto">Lengkapi profil dan tambahkan keahlian untuk mendapatkan rekomendasi lowongan yang akurat.</p>
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Lengkapi Profil
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- My Applications Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Lamaran Saya</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Lacak status lamaran Anda</p>
                                    </div>
                                </div>
                                <a href="{{ route('applications.index') }}" class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 flex items-center gap-1 group">
                                    Lihat Semua
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($myApplications->count() > 0)
                                <div class="space-y-4">
                                    @foreach($myApplications as $application)
                                    <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-750 rounded-xl p-5 border border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ $application->job->title }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->job->company->name ?? __('bkk.fallback.company') }}</p>
                                            </div>
                                            @php
                                                $statusConfig = [
                                                    'submitted' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'label' => 'Terkirim'],
                                                    'under_review' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-700 dark:text-yellow-400', 'label' => 'Sedang Ditinjau'],
                                                    'interviewed' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-700 dark:text-purple-400', 'label' => 'Wawancara'],
                                                    'accepted' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'label' => 'Diterima'],
                                                    'rejected' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'label' => 'Ditolak'],
                                                ];
                                                $status = $statusConfig[$application->status] ?? ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300', 'label' => 'Tidak Diketahui'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Melamar {{ $application->created_at->diffForHumans() }}</span>
                                            <a href="{{ route('applications.show', $application->id) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">
                                                Lihat Detail →
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                        <svg class="w-10 h-10 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada lamaran</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Mulai melamar lowongan yang sesuai keahlian Anda</p>
                                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                        Jelajahi Lowongan
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column (1/3) -->
                <div class="space-y-6">
                    <!-- Activity Timeline -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/20 dark:to-teal-900/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tindakan terbaru Anda</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if(count($activities) > 0)
                                <div class="relative space-y-4">
                                    @foreach($activities as $activity)
                                    <div class="flex gap-4 group">
                                        <div class="flex flex-col items-center">
                                            @php
                                                $colorClasses = [
                                                    'blue' => 'bg-blue-500',
                                                    'green' => 'bg-green-500',
                                                    'yellow' => 'bg-yellow-500',
                                                    'red' => 'bg-red-500',
                                                    'purple' => 'bg-purple-500',
                                                    'gray' => 'bg-gray-500',
                                                ];
                                                $bgColor = $colorClasses[$activity['color']] ?? 'bg-gray-500';
                                            @endphp
                                            <div class="w-8 h-8 rounded-full {{ $bgColor }} flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    @if($activity['icon'] === 'briefcase')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                    @endif
                                                </svg>
                                            </div>
                                            @if(!$loop->last)
                                            <div class="w-0.5 h-full bg-gray-200 dark:bg-gray-700 flex-1"></div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 pb-6">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $activity['timestamp']->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada aktivitas</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Acara Mendatang</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Peluang karir</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($upcomingEvents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($upcomingEvents as $event)
                                    <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-gray-800 dark:to-gray-750 rounded-xl p-4 border border-orange-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-500 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-orange-600 rounded-lg flex flex-col items-center justify-center text-white shadow-md">
                                                    <span class="text-xs font-semibold">{{ $event->start_time->format('M') }}</span>
                                                    <span class="text-lg font-bold leading-none">{{ $event->start_time->format('d') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1 truncate">{{ $event->title }}</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">{{ \App\Support\Label::eventType($event->type) }}</p>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $event->start_time->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/30 dark:to-red-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada acara mendatang</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg overflow-hidden relative group">
                        <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500" aria-hidden="true"></div>
                        <div class="p-6 relative z-10">
                            <h3 class="text-lg font-bold text-white mb-4">Aksi Cepat</h3>
                            <div class="space-y-2">
                                <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group/link">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover/link:scale-110 transition-transform" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium group-hover/link:translate-x-1 transition-transform">Jelajahi Lowongan</span>
                                </a>
                                
                                <a href="{{ route('cv.builder') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group/link">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover/link:scale-110 transition-transform" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium group-hover/link:translate-x-1 transition-transform">Buat CV</span>
                                </a>
                                
                                <a href="{{ route('events.index') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group/link">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover/link:scale-110 transition-transform" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium group-hover/link:translate-x-1 transition-transform">Lihat Acara</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
