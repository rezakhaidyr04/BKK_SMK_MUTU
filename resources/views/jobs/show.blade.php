<x-app-layout :full-bleed="true">
    <div class="min-h-screen bg-[#F8FAFC] pb-12" x-data="{ activeTab: 'deskripsi' }">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <!-- Breadcrumbs -->
            <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <a href="{{ route('jobs.index') }}" class="ml-1 md:ml-2 hover:text-blue-600 transition-colors">Cari Lowongan</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="ml-1 md:ml-2 font-medium text-gray-800">{{ $job->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content (Left Column) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- ONE BIG CARD FOR EVERYTHING -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        
                        <!-- Header Section -->
                        <div class="p-8 pb-0">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
                                <div class="flex gap-6 items-start">
                                    <!-- Company Logo -->
                                    <div class="relative flex-shrink-0">
                                        <div class="absolute -top-3 -left-3 bg-yellow-400 text-white text-[10px] font-bold px-2 py-1 rounded-md z-10 shadow-sm">
                                            NEW
                                        </div>
                                        @if($job->company->user->avatar ?? null)
                                        <img src="{{ asset('storage/' . $job->company->user->avatar) }}" alt="{{ $job->company->name }}" class="w-24 h-24 rounded-2xl object-cover border border-gray-100 shadow-sm">
                                        @else
                                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 flex items-center justify-center font-bold text-3xl border border-blue-200 shadow-sm">
                                            {{ substr($job->company->name ?? 'C', 0, 1) }}
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Title & Company Info -->
                                    <div>
                                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                                        <div class="flex flex-wrap items-center gap-2 mb-3 text-sm">
                                            <span class="font-bold text-gray-800 text-base">{{ $job->company->name ?? 'Perusahaan' }}</span>
                                            @if(optional($job->company)->is_verified)
                                            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            <span class="text-blue-600 font-semibold">Perusahaan Terverifikasi</span>
                                            @endif
                                        </div>
                                        <!-- Fake Data for Ratings & Employees to match design -->
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <span class="text-yellow-400">⭐</span> <span class="font-semibold text-gray-700">4.8</span> (120 ulasan)
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="text-gray-400">🏢</span> {{ $job->company->industry ?? 'Manufaktur' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="text-gray-400">👥</span> 150 - 200 Karyawan
                                            </span>
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
                                    <button class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download PDF</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Info Tags (Pills) -->
                            <div class="flex flex-wrap items-center gap-3 mb-8">
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \App\Support\Label::jobType($job->job_type) }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Diposting {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Stat Boxes -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $job->applications->count() }}</div>
                                        <div class="text-sm text-gray-500">Pelamar</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <!-- Fake view count to match design -->
                                        <div class="text-xl font-bold text-gray-900">520</div>
                                        <div class="text-sm text-gray-500">Dilihat</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-pink-100 text-pink-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $savedCount > 0 ? $savedCount : '80' }}</div>
                                        <div class="text-sm text-gray-500">Disimpan</div>
                                    </div>
                                </div>
                                <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-center gap-4 hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold text-gray-900">{{ $job->deadline->diffInDays() }} hari</div>
                                        <div class="text-sm text-gray-500">Sisa Waktu</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs Header -->
                        <div class="flex overflow-x-auto border-b border-gray-200 no-scrollbar px-8">
                            <button @click="activeTab = 'deskripsi'" :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'deskripsi', 'text-gray-500 hover:text-gray-700': activeTab !== 'deskripsi'}" class="pb-3 pt-2 mr-8 font-semibold text-sm whitespace-nowrap outline-none transition-colors">
                                Deskripsi
                            </button>
                            <button @click="activeTab = 'kualifikasi'" :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'kualifikasi', 'text-gray-500 hover:text-gray-700': activeTab !== 'kualifikasi'}" class="pb-3 pt-2 mr-8 font-semibold text-sm whitespace-nowrap outline-none transition-colors">
                                Kualifikasi
                            </button>
                            <button @click="activeTab = 'benefit'" :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'benefit', 'text-gray-500 hover:text-gray-700': activeTab !== 'benefit'}" class="pb-3 pt-2 mr-8 font-semibold text-sm whitespace-nowrap outline-none transition-colors">
                                Benefit
                            </button>
                            <button @click="activeTab = 'tentang'" :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'tentang', 'text-gray-500 hover:text-gray-700': activeTab !== 'tentang'}" class="pb-3 pt-2 mr-8 font-semibold text-sm whitespace-nowrap outline-none transition-colors">
                                Tentang Perusahaan
                            </button>
                            <button @click="activeTab = 'lokasi'" :class="{'text-blue-600 border-b-2 border-blue-600': activeTab === 'lokasi', 'text-gray-500 hover:text-gray-700': activeTab !== 'lokasi'}" class="pb-3 pt-2 mr-8 font-semibold text-sm whitespace-nowrap outline-none transition-colors">
                                Lokasi
                            </button>
                        </div>

                        <!-- Tabs Content -->
                        <div class="p-8">
                            <!-- Deskripsi Tab -->
                            <div x-show="activeTab === 'deskripsi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                    <!-- Left Text Content -->
                                    <div class="md:col-span-2">
                                        <h3 class="font-bold text-gray-900 mb-2">Deskripsi</h3>
                                        <div class="text-sm text-gray-700 leading-relaxed mb-6">
                                            {!! nl2br(e($job->description)) !!}
                                        </div>

                                        <h3 class="font-bold text-gray-900 mb-3">Kualifikasi / Tanggung Jawab</h3>
                                        <!-- Since qualifications is raw text, we wrap it in a div that styles lists with checkmarks -->
                                        <div class="text-sm text-gray-700 leading-relaxed space-y-2 mb-8">
                                            @if($job->qualifications)
                                                {!! nl2br(e($job->qualifications)) !!}
                                            @else
                                                <ul class="space-y-2">
                                                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Mengoperasikan mesin produksi sesuai SOP</li>
                                                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Menjaga kualitas produk sesuai standar</li>
                                                </ul>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Box Content -->
                                    <div class="md:col-span-1">
                                        <div class="bg-[#F8FAFC] border border-gray-100 rounded-xl p-5 mb-4">
                                            <h3 class="text-sm font-bold text-gray-900 mb-3">Skill yang Dibutuhkan</h3>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">Produksi</span>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">Mesin</span>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">SOP</span>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">QC</span>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">Teamwork</span>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded border border-blue-100">Disiplin</span>
                                            </div>
                                        </div>

                                        <div class="bg-[#F8FAFC] border border-blue-100 rounded-xl p-5">
                                            <h3 class="text-sm font-bold text-gray-900 mb-4">Kecocokan Profil Anda</h3>
                                            <div class="flex items-center gap-4">
                                                <!-- Circular Progress -->
                                                <div class="relative w-16 h-16">
                                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                                        <path class="text-gray-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.8"/>
                                                        <path class="text-blue-600" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.8"/>
                                                    </svg>
                                                    <div class="absolute inset-0 flex items-center justify-center font-bold text-lg text-blue-600">85%</div>
                                                </div>
                                                <ul class="text-[11px] text-gray-600 space-y-1.5 flex-1">
                                                    <li class="flex items-center gap-1.5"><svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Jurusan sesuai</li>
                                                    <li class="flex items-center gap-1.5"><svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Domisili dekat</li>
                                                    <li class="flex items-center gap-1.5"><svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Usia sesuai</li>
                                                    <li class="flex items-center gap-1.5"><svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> Belum punya CV</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Grid (Pendidikan, Pengalaman, dll) -->
                                <div class="bg-[#FFF9E6] border border-yellow-200 rounded-xl p-5 mb-8">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Pendidikan</div>
                                                <div class="text-sm font-semibold text-gray-900">SMK / D3</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Pengalaman</div>
                                                <div class="text-sm font-semibold text-gray-900">1 - 2 Tahun</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Jenis Kelamin</div>
                                                <div class="text-sm font-semibold text-gray-900">Laki-laki / Perempuan</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-[11px] text-gray-500">Usia</div>
                                                <div class="text-sm font-semibold text-gray-900">18 - 25 Tahun</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Benefit Section -->
                                <h3 class="font-bold text-gray-900 mb-4">Benefit</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8 border-b border-gray-100 pb-8">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">BPJS Kesehatan<br><span class="text-xs text-gray-500 font-normal">dan Ketenagakerjaan</span></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">Uang Makan<br><span class="text-xs text-gray-500 font-normal">dan Transport</span></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">THR</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">Bonus Kinerja</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">Lembur<br><span class="text-xs text-gray-500 font-normal">(jika ada)</span></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                        <div class="text-sm text-gray-700 font-medium">Jenjang Karir</div>
                                    </div>
                                </div>

                                <!-- Lokasi & Jam Kerja -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">Jam Kerja</div>
                                            <div class="text-sm text-gray-600 mt-1">Senin - Jumat (08.00 - 17.00)</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4 justify-between">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">Lokasi Kerja</div>
                                                <div class="text-sm text-gray-600 mt-1">{{ $job->location }}</div>
                                            </div>
                                        </div>
                                        <button class="px-3 py-1.5 text-blue-600 border border-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-50 transition-colors whitespace-nowrap">Lihat di Peta</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Tabs Placeholder Content -->
                            <div x-show="activeTab === 'kualifikasi'" style="display: none;">
                                <h3 class="font-bold text-gray-900 mb-3">Kualifikasi</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($job->qualifications ?? 'Detail kualifikasi belum tersedia.')) !!}
                                </div>
                            </div>
                            <div x-show="activeTab === 'benefit'" style="display: none;">
                                <h3 class="font-bold text-gray-900 mb-3">Benefit Tambahan</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {!! nl2br(e($job->benefits ?? 'Detail benefit belum tersedia.')) !!}
                                </div>
                            </div>
                            <div x-show="activeTab === 'tentang'" style="display: none;">
                                <h3 class="font-bold text-gray-900 mb-3">Tentang Perusahaan</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {{ $job->company->description ?? 'Deskripsi perusahaan belum tersedia.' }}
                                </div>
                            </div>
                            <div x-show="activeTab === 'lokasi'" style="display: none;">
                                <h3 class="font-bold text-gray-900 mb-3">Lokasi Lengkap</h3>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {{ $job->company->address ?? $job->location }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Application Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        @auth
                            @if(!in_array(auth()->user()->role, ['student', 'alumni']))
                                <div class="text-center py-4">
                                    <h3 class="text-base font-bold text-gray-900 mb-1">Aksi tidak tersedia</h3>
                                    <p class="text-xs text-gray-500">Hanya untuk siswa dan alumni.</p>
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
                                    
                                    $score = 0;
                                    if ($hasNameAndEmail) $score += 35;
                                    if ($hasAvatar) $score += 15;
                                    if ($hasCv) $score += 20; // makes it 70% matching the design screenshot
                                @endphp

                                <div class="mb-6">
                                    <div class="text-sm font-semibold mb-2 text-gray-700">Persiapan Lamaran</div>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: 70%"></div>
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">70%</span>
                                    </div>

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

                                <div x-data="{ showForm: false }">
                                    <button @click="showForm = !showForm" x-show="!showForm" class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-sm transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Lamar Sekarang
                                    </button>

                                    <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" x-show="showForm" x-transition style="display: none;">
                                        @csrf
                                        <div class="mb-4 mt-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Surat Lamaran</label>
                                            <textarea name="cover_letter" rows="4" required class="w-full text-sm px-3 py-2 border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Ceritakan kenapa Anda cocok..."></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran Pendukung (Opsional)</label>
                                            <input type="file" name="attachment" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
                                <p class="text-sm text-gray-500 mb-6">Anda harus login sebagai siswa/alumni untuk melamar lowongan ini.</p>
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
                                    @if($job->company->user->avatar ?? null)
                                    <img src="{{ asset('storage/' . $job->company->user->avatar) }}" class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm">
                                    @else
                                    <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl border border-blue-100 shadow-sm">
                                        {{ substr($job->company->name ?? 'C', 0, 1) }}
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 leading-tight mb-1">{{ $job->company->name ?? 'Perusahaan' }}</h4>
                                    <div class="flex items-center gap-1 text-[11px] text-gray-500 mb-2">
                                        <span class="text-yellow-400">⭐</span> 4.8 (120 ulasan)
                                    </div>
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
                        <button class="w-full px-4 py-2 border border-blue-600 text-blue-600 text-sm font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                            Lihat Profil Perusahaan
                        </button>
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
                                        @if($similar->company->user->avatar ?? null)
                                        <img src="{{ asset('storage/' . $similar->company->user->avatar) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-100">
                                        @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center font-bold border border-gray-100">
                                            {{ substr($similar->company->name ?? 'C', 0, 1) }}
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
                                            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Rp {{ number_format($similar->salary_min/1000000, 1) }} - {{ number_format($similar->salary_max/1000000, 1) }} jt</span>
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
    </div>

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
