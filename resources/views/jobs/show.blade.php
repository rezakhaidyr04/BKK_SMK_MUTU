<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Back Button -->
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Lowongan
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Job Header Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white">
                            <div class="flex items-start gap-6">
                                <!-- Company Logo -->
                                <div class="flex-shrink-0">
                                    @if($job->company->user->avatar ?? null)
                                    <img src="{{ asset('storage/' . $job->company->user->avatar) }}"
                                         alt="{{ $job->company->name }}"
                                         class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-xl">
                                    @else
                                    <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-xl">
                                        {{ substr($job->company->name ?? 'C', 0, 1) }}
                                    </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h1 class="text-3xl font-bold mb-2">{{ $job->title }}</h1>
                                    <p class="text-xl text-blue-100 mb-4">{{ $job->company->name ?? __('bkk.fallback.company') }}</p>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $job->location }}
                                        </span>

                                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \App\Support\Label::jobType($job->job_type) }}
                                        </span>

                                        @if($job->salary_min && $job->salary_max)
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm font-semibold">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-3 gap-4 mb-8">
                                <div class="text-center p-4 bg-blue-50 rounded-xl">
                                    <p class="text-2xl font-bold text-blue-600">{{ $job->applications->count() }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Pelamar</p>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-xl">
                                    <p class="text-2xl font-bold text-green-600">{{ $job->created_at->diffForHumans() }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Diposting</p>
                                </div>
                                <div class="text-center p-4 bg-red-50 rounded-xl">
                                    <p class="text-2xl font-bold text-red-600">{{ $job->deadline->diffForHumans() }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Tenggat</p>
                                </div>
                            </div>

                            <!-- Job Description -->
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                        </svg>
                                    </div>
                                    Deskripsi Pekerjaan
                                </h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($job->description)) !!}
                                </div>
                            </div>

                            <!-- Qualifications -->
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    Kualifikasi
                                </h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($job->qualifications)) !!}
                                </div>
                            </div>

                            <!-- Benefits -->
                            @if($job->benefits)
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                        </svg>
                                    </div>
                                    Benefit
                                </h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($job->benefits)) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Similar Jobs -->
                    @if($similarJobs->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Lowongan Serupa</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($similarJobs as $similar)
                            <a href="{{ route('jobs.show', $similar->id) }}" class="block p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300">
                                <h4 class="font-semibold text-gray-900 mb-1">{{ $similar->title }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ $similar->company->name ?? 'Perusahaan' }}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span>{{ $similar->location }}</span>
                                    <span>•</span>
                                    <span>{{ \App\Support\Label::jobType($similar->job_type) }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Application Card -->
                    @auth
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        @if(!in_array(auth()->user()->role, ['student', 'alumni']))
                        <div class="text-center py-6">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6L3 9m18 0l-9 5"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Aksi lamaran tidak tersedia</h3>
                            <p class="text-sm text-gray-600">Menu ini hanya untuk siswa dan alumni.</p>
                        </div>
                        @elseif($hasApplied)
                        <!-- Already Applied -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Lamaran Terkirim</h3>
                            <p class="text-sm text-gray-600 mb-4">Anda sudah melamar pada posisi ini</p>
                            <a href="{{ route('applications.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                Lihat Lamaran Saya
                            </a>
                        </div>
                        @else
                        <!-- Apply Form -->
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Lamar posisi ini</h3>

                        <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data" id="applicationForm">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Surat Lamaran</label>
                                <textarea name="cover_letter" rows="6" required
                                          class="w-full px-4 py-3 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                          placeholder="Ceritakan mengapa Anda cocok untuk posisi ini..."></textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimal 100 karakter</p>
                                @error('cover_letter')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran CV / Berkas Pendukung</label>
                                <input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
                                <p class="text-xs text-gray-500 mt-1">Opsional. PDF, DOC, DOCX, JPG, atau PNG. Maksimal 5 MB.</p>
                                @error('attachment')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Profile Check -->
                            @php
                                $profileOk = auth()->user()->name && auth()->user()->email && auth()->user()->phone;
                                $cvOk      = auth()->user()->cvFiles()->exists();
                            @endphp
                            <div class="mb-6 p-4 bg-blue-50 rounded-xl">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Persiapan lamaran:</h4>
                                <ul class="space-y-2 text-sm">
                                    {{-- Cek profil lengkap --}}
                                    <li class="flex items-center gap-2 {{ $profileOk ? 'text-gray-700' : 'text-gray-500' }}">
                                        @if($profileOk)
                                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Profil sudah dilengkapi
                                        @else
                                            <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <a href="{{ route('profile.edit') }}" class="hover:text-blue-600 hover:underline">Lengkapi profil Anda (nama, no. HP)</a>
                                        @endif
                                    </li>

                                    {{-- Cek CV --}}
                                    <li class="flex items-center gap-2 {{ $cvOk ? 'text-gray-700' : 'text-gray-500' }}">
                                        @if($cvOk)
                                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            CV sudah diunggah
                                        @else
                                            <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <a href="{{ route('cv.builder') }}" class="hover:text-blue-600 hover:underline">Buat CV terlebih dahulu</a>
                                        @endif
                                    </li>

                                    {{-- Selalu tampil --}}
                                    <li class="flex items-center gap-2 text-gray-600">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Baca persyaratan di bawah sebelum melamar
                                    </li>
                                </ul>
                            </div>

                            <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Lamaran
                            </button>
                        </form>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-2 mt-4">
                            <button onclick="toggleBookmark({{ $job->id }})"
                                    class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl hover:border-red-500 hover:bg-red-50 transition-colors flex items-center justify-center gap-2 bookmark-btn">
                                <svg class="w-5 h-5" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                <span class="font-medium text-gray-700">{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                            </button>

                            <button onclick="shareJob()" id="shareBtn" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                <span class="font-medium text-gray-700">Bagikan</span>
                            </button>
                        </div>
                    </div>
                    @else
                    <!-- Login Required -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Masuk untuk melamar</h3>
                            <p class="text-sm text-gray-600 mb-6">Buat akun atau masuk untuk melamar posisi ini</p>
                            <div class="space-y-3">
                                <a href="{{ route('login') }}" class="block w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    Masuk
                                </a>
                                <a href="{{ route('register') }}" class="block w-full px-6 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                    Buat Akun
                                </a>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- Company Info -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tentang Perusahaan</h3>

                        <div class="text-center mb-4">
                            @if($job->company->user->avatar ?? null)
                            <img src="{{ asset('storage/' . $job->company->user->avatar) }}"
                                 alt="{{ $job->company->name }}"
                                 class="w-20 h-20 mx-auto rounded-xl object-cover border-2 border-gray-200 shadow-md">
                            @else
                            <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-md">
                                {{ substr($job->company->name ?? 'C', 0, 1) }}
                            </div>
                            @endif
                            <h4 class="font-bold text-gray-900 mt-3">{{ $job->company->name ?? __('bkk.fallback.company') }}</h4>
                            @if($job->company->industry ?? null)
                            <p class="text-sm text-gray-600">{{ $job->company->industry }}</p>
                            @endif
                        </div>

                        @if($job->company->description ?? null)
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($job->company->description, 150) }}</p>
                        @endif

                        @if($job->company->website ?? null)
                        <a href="{{ $job->company->website }}" target="_blank" class="block w-full px-4 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors text-center">
                            Kunjungi Website
                        </a>
                        @endif
                    </div>

                    <!-- Job Details -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Pekerjaan</h3>

                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Diposting</p>
                                    <p class="font-semibold text-gray-900">{{ $job->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tenggat Waktu</p>
                                    <p class="font-semibold text-gray-900">{{ $job->deadline->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Pelamar</p>
                                    <p class="font-semibold text-gray-900">{{ $job->applications->count() }} orang</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    const btn = document.getElementById('shareBtn');
                    const span = btn.querySelector('span');
                    const original = span.textContent;
                    span.textContent = 'Link tersalin!';
                    setTimeout(() => { span.textContent = original; }, 2000);
                }).catch(() => {
                    prompt('Salin link ini:', url);
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
                const btn = document.querySelector('.bookmark-btn svg');
                if (data.bookmarked) {
                    btn.setAttribute('fill', 'currentColor');
                } else {
                    btn.setAttribute('fill', 'none');
                }
                alert(data.message);
            });
        }
    </script>
    @endpush
</x-app-layout>
