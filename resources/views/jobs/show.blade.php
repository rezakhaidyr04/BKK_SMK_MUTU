<x-app-layout :full-bleed="true" :hide-sidebar="!auth()->check()">
    <div class="page-shell">
        <x-ui.page-banner 
            :title="$job->title"
            :subtitle="$job->company_name ?? 'Detail lowongan pekerjaan'"
            :back-url="(auth()->check() && auth()->user()->isCompany()) ? route('company.jobs.index') : route('jobs.index')"
            back-label="Kembali ke Lowongan" eyebrow="Beranda › Lowongan">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ $job->location }}
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ \App\Support\Label::jobType($job->job_type) }} · {{ $job->company_name ?? 'Perusahaan' }}
                </span>
            </x-slot:chips>
        </x-ui.page-banner>
        <div class="page-container page-section job-show-page" x-data="{ activeTab: 'deskripsi' }">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-8">
                <!-- Main Content (Left Column) -->
                <div class="lg:col-span-2 space-y-5 sm:space-y-6 min-w-0">
                    <!-- ONE BIG CARD FOR EVERYTHING -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        
                        <!-- Header Section -->
                        <div class="p-4 sm:p-8 pb-0">
                            <div class="job-show-head flex flex-col md:flex-row md:items-start justify-between gap-4 sm:gap-6 mb-5 sm:mb-6">
                                <div class="flex gap-3.5 sm:gap-6 items-start min-w-0">
                                    <!-- Company Logo -->
                                    <div class="relative flex-shrink-0">
                                        @if($job->company?->logo)
                                        <img src="{{ asset('storage/' . $job->company->logo) }}" alt="Logo {{ $job->company->name }}" class="job-show-logo w-24 h-24 rounded-full object-cover border border-blue-200 shadow-sm bg-white">
                                        @else
                                        <div class="job-show-logo w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 flex items-center justify-center font-bold text-3xl border border-blue-200 shadow-sm">
                                            {{ strtoupper(substr($job->company_name ?? $job->company->name ?? 'C', 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Title & Company Info -->
                                    <div class="min-w-0">
                                        <h1 class="job-show-title text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                                        <div class="flex flex-wrap items-center gap-2 mb-3 text-sm">
                                            <span class="font-bold text-gray-800 text-base">{{ $job->company_name ?? 'Perusahaan' }}</span>
                                        </div>
                                        <!-- Ringkasan data yang memang tersedia -->
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons Desktop -->
                                <div class="hidden md:flex flex-row gap-2">
                                    <button onclick="toggleBookmark({{ $job->id }})" class="bookmark-btn px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <span>{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                                    </button>
                                    <button onclick="shareJob()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors" id="shareBtn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                        <span>Bagikan</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Info Tags (Pills) -->
                            <div class="job-pill-row flex flex-wrap items-center gap-2.5 sm:gap-3 mb-6 sm:mb-8">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $job->location }}
                                </span>
                                @if($job->salary_min && $job->salary_max)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                                </span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \App\Support\Label::jobType($job->job_type) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Diposting {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Stat Boxes -->
                            <div class="job-stat-grid grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $applicationsCount ?? 0 }}</div>
                                        <div class="text-sm text-gray-500">Pelamar</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $job->company_name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">Perusahaan</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-violet-100 text-violet-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $savedCount }}</div>
                                        <div class="text-sm text-gray-500">Disimpan</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-amber-100 text-amber-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $job->deadline ? $job->deadline->diffInDays() . ' hari' : '-' }}</div>
                                        <div class="text-sm text-gray-500">Sisa Waktu</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs Header -->
                        <div class="px-4 sm:px-8 pt-2">
                            <div class="job-tabs-scroll flex gap-2 overflow-x-auto no-scrollbar rounded-2xl bg-slate-50 p-2 border border-slate-200">
                            <button @click="activeTab = 'deskripsi'" :class="activeTab === 'deskripsi' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70 border-transparent'" class="shrink-0 px-4 py-2 rounded-xl border text-sm font-semibold whitespace-nowrap outline-none transition-all">
                                Deskripsi
                            </button>
                            <button @click="activeTab = 'kualifikasi'" :class="activeTab === 'kualifikasi' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70 border-transparent'" class="shrink-0 px-4 py-2 rounded-xl border text-sm font-semibold whitespace-nowrap outline-none transition-all">
                                Kualifikasi
                            </button>
                            <button @click="activeTab = 'benefit'" :class="activeTab === 'benefit' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70 border-transparent'" class="shrink-0 px-4 py-2 rounded-xl border text-sm font-semibold whitespace-nowrap outline-none transition-all">
                                Benefit
                            </button>
                            <button @click="activeTab = 'tentang'" :class="activeTab === 'tentang' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70 border-transparent'" class="shrink-0 px-4 py-2 rounded-xl border text-sm font-semibold whitespace-nowrap outline-none transition-all">
                                Tentang Perusahaan
                            </button>
                            <button @click="activeTab = 'lokasi'" :class="activeTab === 'lokasi' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70 border-transparent'" class="shrink-0 px-4 py-2 rounded-xl border text-sm font-semibold whitespace-nowrap outline-none transition-all">
                                Lokasi
                            </button>
                            </div>
                        </div>

                        <!-- Tabs Content -->
                        <div class="p-6 sm:p-8">
                            <!-- Deskripsi Tab -->
                            <div x-show="activeTab === 'deskripsi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Left Text Content -->
                                    <div class="md:col-span-2">
                                        <h3 class="font-bold text-gray-900 mb-2">Deskripsi</h3>
                                        <div class="text-sm text-gray-700 leading-relaxed mb-6">
                                            {!! nl2br(e($job->description)) !!}
                                        </div>

                                        <h3 class="font-bold text-gray-900 mb-3">Kualifikasi / Tanggung Jawab</h3>
                                        <!-- Kualifikasi dinamis dari data perusahaan -->
                                        <div class="text-sm text-gray-700 leading-relaxed space-y-2 mb-8">
                                            @if($job->qualifications)
                                                {!! nl2br(e($job->qualifications)) !!}
                                            @else
                                                <p class="text-sm text-slate-500">Kualifikasi belum diisi oleh perusahaan.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Grid (Pendidikan, Pengalaman, dll) — dinamis dari data perusahaan -->
                                <div class="bg-[#FFF9E6] border border-yellow-200 rounded-2xl p-5 mb-8">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Pendidikan</div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $job->education ?: '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Pengalaman</div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $job->experience ?: '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Jenis Kelamin</div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $job->gender ?: '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Usia</div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $job->age_range ?: '—' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Benefit Section — dinamis dari data perusahaan -->
                                <h3 class="font-bold text-gray-900 mb-4">Benefit</h3>
                                @php
                                    $benefitItems = collect(preg_split('/[\r\n,;]+/', $job->benefits ?? ''))
                                        ->map(fn ($b) => trim($b))
                                        ->filter()
                                        ->values();
                                @endphp
                                @if($benefitItems->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-8 border-b border-gray-100 pb-8">
                                    @foreach($benefitItems as $benefit)
                                    <div class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50/50 px-3 py-2.5">
                                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="text-sm text-gray-700 font-medium leading-snug">{{ $benefit }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-slate-500 mb-8 border-b border-gray-100 pb-8">Benefit belum diisi oleh perusahaan.</p>
                                @endif

                                <!-- Lokasi & Jam Kerja — dinamis dari data perusahaan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">Jam Kerja</div>
                                            <div class="text-sm text-gray-600 mt-1">{{ $job->work_hours ?: 'Belum ditentukan perusahaan' }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4 justify-between">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">Lokasi Kerja</div>
                                                <div class="text-sm text-gray-600 mt-1">📍 {{ $job->locationLabel() }}</div>
                                            </div>
                                        </div>
                                        @php
                                            $lokasiMapLink = $job->company?->maps_url ?? (($job->company?->address ?? $job->location) ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($job->company?->address ?? $job->location) : null);
                                        @endphp
                                        @if($lokasiMapLink)
                                        <a href="{{ $lokasiMapLink }}" target="_blank" rel="noopener" class="px-3 py-1.5 text-blue-600 border border-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-50 transition-colors whitespace-nowrap">Lihat di Peta</a>
                                        @else
                                        <button disabled class="px-3 py-1.5 text-gray-400 border border-gray-200 rounded-lg text-xs font-semibold whitespace-nowrap cursor-not-allowed">Lihat di Peta</button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Other Tabs -->
                            <div x-show="activeTab === 'kualifikasi'" x-cloak>
                                <h3 class="font-bold text-gray-900 mb-3">Kualifikasi</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($job->qualifications ?? 'Detail kualifikasi belum diisi oleh perusahaan.')) !!}
                                </div>
                            </div>
                            <div x-show="activeTab === 'benefit'" x-cloak>
                                <h3 class="font-bold text-gray-900 mb-3">Benefit Tambahan</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($job->benefits ?? 'Benefit belum diisi oleh perusahaan.')) !!}
                                </div>
                            </div>
                            <div x-show="activeTab === 'tentang'" x-cloak>
                                <h3 class="font-bold text-gray-900 mb-3">Tentang Perusahaan</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    @if($job->company?->description)
                                        {!! nl2br(e($job->company->description)) !!}
                                    @else
                                        Informasi perusahaan belum tersedia.
                                    @endif
                                </div>
                            </div>
                            <div x-show="activeTab === 'lokasi'" x-cloak>
                                <h3 class="font-bold text-gray-900 mb-3">Lokasi Lengkap</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {{ $job->company?->address ?? $job->location ?? 'Lokasi belum diisi perusahaan.' }}
                                </div>
                                @php
                                    $jobMapLink = $job->company?->maps_url ?? (($job->company?->address ?? $job->location) ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($job->company?->address ?? $job->location) : null);
                                @endphp
                                @if($jobMapLink)
                                <a href="{{ $jobMapLink }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka Rute di Google Maps
                                </a>
                                @endif
                                @if($job->work_hours)
                                <h3 class="font-bold text-gray-900 mt-4 mb-2">Jam Kerja</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {{ $job->work_hours }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    
                     <!-- Application Card -->
                     @auth
                         @if(auth()->user()->isCompany() && $job->company_id === auth()->user()->company?->id)
                             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                                 <div class="flex items-center justify-between mb-4">
                                     <h3 class="text-lg font-bold text-slate-900">Lowongan Anda</h3>
                                     <x-ui.status-badge :status="$job->status" />
                                 </div>
                                  <div class="grid grid-cols-3 gap-4 mb-6">
                                      <div class="text-center p-3 bg-blue-50 rounded-xl">
                                          <p class="text-2xl font-bold text-blue-600">{{ $ownerApplicationsCount ?? 0 }}</p>
                                          <p class="text-xs text-blue-600 font-semibold">Pelamar</p>
                                      </div>
                                      <div class="text-center p-3 bg-violet-50 rounded-xl">
                                          <p class="text-2xl font-bold text-violet-600">{{ $reviewedCount ?? 0 }}</p>
                                          <p class="text-xs text-violet-600 font-semibold">Ditinjau</p>
                                      </div>
                                      <div class="text-center p-3 bg-green-50 rounded-xl">
                                          <p class="text-2xl font-bold text-green-600">{{ $acceptedCount ?? 0 }}</p>
                                          <p class="text-xs text-green-600 font-semibold">Diterima</p>
                                      </div>
                                  </div>
                                 <div class="flex flex-col gap-2">
                                     <a href="{{ route('company.applicants.index') }}" class="w-full px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-center">
                                         Lihat Semua Pelamar
                                     </a>
                                     @if($job->status === 'pending')
                                     <a href="{{ route('company.jobs.index') }}" class="w-full px-4 py-2.5 border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors text-center">
                                         Kelola Lowongan
                                     </a>
                                     @endif
                                     <a href="{{ route('company.jobs.index') }}" class="w-full px-4 py-2.5 border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors text-center">
                                         Kembali ke Daftar Lowongan
                                     </a>
                                 </div>
                             </div>
                         @elseif(!auth()->user()->isUmum())
                             <div class="text-center py-4">
                                 <h3 class="text-base font-bold text-gray-900 mb-1">Aksi tidak tersedia</h3>
                                 <p class="text-xs text-gray-500">Melamar lowongan hanya tersedia untuk akun pencari kerja.</p>
                             </div>
                          @elseif($hasApplied)
                                 <div class="text-center py-6">
                                     <div class="w-16 h-16 mx-auto mb-4 bg-green-50 rounded-full flex items-center justify-center">
                                         <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                     </div>
                                     <h3 class="text-lg font-bold text-gray-900 mb-2">Lamaran Terkirim</h3>
                                     <p class="text-sm text-gray-500 mb-6">Anda sudah melamar posisi ini. Silakan cek status lamaran Anda.</p>
                                      <a href="{{ route('applications.index') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold text-center rounded-xl hover:bg-blue-700 transition-colors">
                                          Lihat Lamaran Saya
                                      </a>
                                 </div>
                            @else
                                <h3 class="text-lg font-bold text-gray-900 mb-6">Lamar Posisi Ini</h3>
                                
                                @php
                                    $user = auth()->user();
                                    $hasNameAndEmail = $user->name && $user->email;
                                    $hasAvatar = $user->avatar !== null;
                                    $hasCv = $user->cvFiles()->exists();
                                    
                                @endphp

                                <div class="mb-6">
                                    <div class="text-sm font-semibold mb-2 text-gray-700">Persiapan Lamaran</div>

                                    @php
                                        $profileScore = 0;
                                        if($hasNameAndEmail) $profileScore += 25;
                                        if($hasAvatar) $profileScore += 25;
                                        if($hasCv) $profileScore += 50;
                                    @endphp

                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $profileScore }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $profileScore }}%</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-4">Kelengkapan profil Anda untuk melamar pekerjaan.</p>

                                    <ul class="space-y-3 text-sm">
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 {{ $hasNameAndEmail ? 'text-gray-700' : 'text-gray-400' }}">
                                                <svg class="w-4 h-4 {{ $hasNameAndEmail ? 'text-green-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Profil Lengkap
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 {{ $hasAvatar ? 'text-gray-700' : 'text-gray-400' }}">
                                                <svg class="w-4 h-4 {{ $hasAvatar ? 'text-green-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Foto Profil
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-gray-700">
                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Email Aktif
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 {{ $hasCv ? 'text-gray-700' : 'text-gray-400' }}">
                                                @if($hasCv)
                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                @else
                                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                @endif
                                                CV / Resume
                                            </div>
                                            @if(!$hasCv)
                                            <span class="text-xs text-red-500 font-medium">Belum diunggah</span>
                                            @endif
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-gray-400">
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                                Portofolio (Opsional)
                                            </div>
                                            <span class="text-xs text-gray-400">Belum diunggah</span>
                                        </li>
                                    </ul>
                                </div>

                                <div x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }">
                                    <button id="lamar-sekarang" @click="showForm = !showForm" x-show="!showForm" class="w-full px-4 py-3 min-h-[50px] bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition-all flex items-center justify-center gap-2 scroll-mt-32">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Lamar Sekarang
                                    </button>

                                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" x-show="showForm" x-cloak x-transition>
                                        @csrf
                                        @if ($errors->any())
                                        <div class="mb-4 mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-sm font-semibold text-red-700 mb-1">Lamaran belum terkirim, mohon perbaiki dulu:</p>
                                            <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                        <div class="mb-4 mt-2 overflow-hidden rounded-2xl bg-blue-700 p-4 text-white shadow-lg shadow-blue-600/30 ring-1 ring-blue-800">
                                            <div class="flex items-start gap-3">
                                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-blue-700">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v6h6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6M9 17h4"/></svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-bold leading-tight text-white">Template Surat Lamaran BKKMu</p>
                                                    <p class="mt-0.5 text-xs font-medium text-white">Resmi • .docx • Tinggal isi 2 menit</p>
                                                </div>
                                                <span class="shrink-0 rounded-full bg-amber-400 px-2.5 py-1 text-[10px] font-bold tracking-wide text-blue-950">GRATIS</span>
                                            </div>
                                            <div class="mt-3 flex items-center gap-2 text-[11px] font-semibold text-white">
                                                <span class="flex items-center gap-1"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-[11px] font-bold text-blue-700">1</span> Download</span>
                                                <span class="font-bold text-white">→</span>
                                                <span class="flex items-center gap-1"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-[11px] font-bold text-blue-700">2</span> Isi data</span>
                                                <span class="font-bold text-white">→</span>
                                                <span class="flex items-center gap-1"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-[11px] font-bold text-blue-700">3</span> Export PDF</span>
                                            </div>
                                            <a href="{{ asset('templates/surat-lamaran-template.docx') }}" download class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-blue-800 shadow-sm transition hover:bg-amber-300 hover:text-blue-950 active:scale-[.99]">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>
                                                Download Template
                                            </a>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Surat Lamaran (PDF) <span class="text-red-500">*</span> <span class="text-gray-400 font-normal">(Wajib PDF, maks 5MB)</span></label>
                                            <input type="file" name="cover_letter_file" required accept=".pdf,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('cover_letter_file') ring-1 ring-red-300 @enderror">
                                            @error('cover_letter_file')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4 rounded-xl border border-emerald-100 bg-emerald-50/60 p-3">
                                            @if($hasCv)
                                            <p class="text-sm font-semibold text-emerald-900">CV kamu sudah otomatis terlampir dari profil.</p>
                                            <p class="mt-0.5 text-xs text-emerald-700">Upload di bawah hanya jika mau pakai CV khusus untuk lowongan ini. Kalau dikosongkan, perusahaan tetap bisa lihat CV di profilmu.</p>
                                            @else
                                            <p class="text-sm font-semibold text-emerald-900">Belum punya CV?</p>
                                            <p class="mt-0.5 text-xs text-emerald-700">Buat dulu di CV Builder (otomatis ATS-friendly), lalu upload hasilnya di bawah.</p>
                                            <a href="{{ route('cv.builder') }}" class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-200 transition hover:bg-emerald-600 hover:text-white">
                                                Buat CV di CV Builder
                                            </a>
                                            @endif
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">File CV (Opsional) <span class="text-gray-400 font-normal">(PDF, maks 5MB — khusus lamaran ini)</span></label>
                                            <input type="file" name="attachment" accept=".pdf,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            @error('attachment')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">SKCK (Opsional) <span class="text-gray-400 font-normal">(PDF, maks 5MB — boleh dikosongkan)</span></label>
                                            <input type="file" name="skck_file" accept=".pdf,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                            @error('skck_file')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" @click="showForm = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">Batal</button>
                                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-sm">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-4 flex items-start justify-center gap-2 px-4">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <p class="text-[11px] text-gray-500 text-center leading-tight">
                                        Data Anda aman dan hanya dapat dilihat oleh perusahaan terkait.
                                    </p>
                                </div>

                            @endif
                        @else
                            <div class="text-center py-4">
                                <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Masuk untuk melamar</h3>
                                <p class="text-sm text-gray-500 mb-6">Anda harus login sebagai pencari kerja untuk melamar lowongan ini.</p>
                                <a href="{{ route('login') }}" class="block w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-center">Masuk Sekarang</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Company Sidebar Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Tentang Perusahaan</h3>
                        <div class="flex flex-col mb-4 items-start gap-4">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                        @if($job->company?->logo)
                                        <img src="{{ asset('storage/' . $job->company->logo) }}" alt="Logo {{ $job->company->name }}" class="w-14 h-14 rounded-full object-cover border border-gray-100 shadow-sm bg-white">
                                        @elseif($job->company->user->avatar ?? null)
                                        <img src="{{ asset('storage/' . $job->company->user->avatar) }}" alt="Logo {{ $job->company->name }}" class="w-14 h-14 rounded-full object-cover border border-gray-100 shadow-sm">
                                        @else
                                        <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl border border-blue-100 shadow-sm" aria-hidden="true">
                                            {{ strtoupper(substr($job->company->name ?? 'C', 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 leading-tight mb-1">{{ $job->company->name ?? 'Perusahaan' }}</h4>
                                    @if(($companyRating['count'] ?? 0) > 0)
                                        <a href="{{ $job->company_id ? route('companies.show', $job->company_id) : '#' }}" class="flex items-center gap-1 text-[11px] text-gray-500 mb-2 hover:text-blue-600">
                                            <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span class="font-bold text-gray-700">{{ number_format($companyRating['average'], 1) }}</span>
                                            <span>({{ $companyRating['count'] }} ulasan)</span>
                                        </a>
                                    @else
                                        <div class="flex items-center gap-1 text-[11px] text-gray-500 mb-2">
                                            Data ulasan belum tersedia
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-xs text-gray-600 w-full space-y-2">
                                @if($job->company->industry ?? null)
                                <div class="flex items-center gap-2"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> {{ $job->company->industry }}</div>
                                @endif
                                <div class="flex items-start gap-2"><svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg> <span class="leading-tight">{{ $job->company->address ?? $job->location }}</span></div>
                                @if($job->company->website ?? null)
                                <div class="flex items-center gap-2"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> <a href="{{ $job->company->website }}" target="_blank" class="hover:text-blue-600 truncate max-w-[200px]">{{ str_replace(['http://', 'https://'], '', $job->company->website) }}</a></div>
                                @endif
                            </div>
                        </div>

                        @if($job->company_id && $job->company && !$job->company->trashed())
                        <a href="{{ route('companies.show', $job->company_id) }}" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Lihat Profil Perusahaan
                        </a>
                        @endif
                    </div>

                    <!-- Similar Jobs -->
                    @if($similarJobs->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-bold text-gray-900">Lowongan Serupa</h3>
                            <a href="{{ route('jobs.index', ['job_type' => $job->job_type]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat Semua</a>
                        </div>
                        <div class="space-y-3">
                            @foreach($similarJobs as $similar)
                            <a href="{{ route('jobs.show', $similar->id) }}" class="block group border border-gray-100 rounded-xl p-3 hover:border-blue-300 hover:shadow-sm transition-all">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0">
                                        @if($similar->company?->logo)
                                        <img src="{{ asset('storage/' . $similar->company->logo) }}" alt="Logo {{ $similar->company->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-100 bg-white">
                                        @else
                                        <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-600 flex items-center justify-center font-bold border border-gray-100" aria-hidden="true">
                                            {{ strtoupper(substr($similar->company->name ?? 'C', 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-gray-900 text-sm truncate group-hover:text-blue-600 transition-colors">{{ $similar->title }}</h4>
                                            <div class="flex gap-2 items-center">
                                                <span class="inline-block px-1.5 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Baru</span>
                                                <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                            </div>
                                        </div>
                                        <p class="text-[11px] text-gray-500 truncate mb-1">{{ $similar->company->name ?? 'Perusahaan' }}</p>
                                        <div class="flex flex-wrap items-center gap-2 text-[10px] text-gray-400">
                                            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>{{ $similar->location }}</span>
                                            @if($similar->salary_min && $similar->salary_max)
                                            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Rp {{ number_format($similar->salary_min, 0, ',', '.') }} - {{ number_format($similar->salary_max, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sticky CTA khusus HP: Lamar / Simpan / Bagikan selalu terjangkau jempol --}}
        @if(auth()->check() && auth()->user()->isUmum())
            @if(!($hasApplied ?? false))
            <div class="job-sticky-cta md:hidden">
                <button onclick="toggleBookmark({{ $job->id }})" aria-label="Simpan lowongan" class="cta-icon bookmark-btn text-gray-600">
                    <svg class="w-5 h-5" fill="{{ ($isBookmarked ?? false) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </button>
                <a href="#lamar-sekarang" onclick="document.getElementById('lamar-sekarang')?.scrollIntoView({behavior:'smooth',block:'center'});return false;" class="cta-main bg-blue-600 text-white inline-flex items-center justify-center gap-2 hover:bg-blue-700 active:scale-[0.98] transition">
                    Lamar Sekarang
                </a>
                <button onclick="shareJob()" aria-label="Bagikan lowongan" class="cta-icon text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </button>
            </div>
            @endif
        @elseif(!auth()->check())
            <div class="job-sticky-cta md:hidden">
                <a href="{{ route('login') }}" class="cta-main col-span-3 bg-blue-600 text-white inline-flex items-center justify-center gap-2 hover:bg-blue-700 active:scale-[0.98] transition">
                    Masuk untuk Melamar
                </a>
            </div>
        @endif

    @push('scripts')
    <script>
        function shareJob() {
            const title = {{ Js::from($job->title) }};
            const text  = 'Lowongan: ' + title + ' di ' + {{ Js::from($job->company->name ?? 'Perusahaan') }};
            const url   = window.location.href;

            if (navigator.share) {
                navigator.share({ title, text, url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    const span = document.querySelector('#shareBtn span');
                    const original = span.textContent;
                    span.textContent = 'Tersalin!';
                    setTimeout(() => { span.textContent = original; }, 2000);
                });
            }
        }

        function toggleBookmark(jobId) {
            fetch(`/jobs/${jobId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const btns = document.querySelectorAll('.bookmark-btn svg');
                const spans = document.querySelectorAll('.bookmark-btn span');
                btns.forEach(btn => {
                    if (data.bookmarked) {
                        btn.setAttribute('fill', 'currentColor');
                    } else {
                        btn.setAttribute('fill', 'none');
                    }
                });
                spans.forEach(span => {
                    span.textContent = data.bookmarked ? 'Tersimpan' : 'Simpan';
                });
            });
        }
    </script>
    @endpush
</x-app-layout>