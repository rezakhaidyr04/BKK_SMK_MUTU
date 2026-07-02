<x-app-layout :full-bleed="true">
    <div class="page-shell">
    <!-- Hero Search Section -->
    <x-ui.page-hero title="Temukan Pekerjaan Impian Anda" :subtitle="'Temukan ' . $jobs->total() . ' peluang yang menunggu Anda'">
        <x-slot:extra>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
                    <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-white/65">Langkah 1</p>
                        <p class="font-semibold mt-1">Cari lowongan yang relevan</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-white/65">Langkah 2</p>
                        <p class="font-semibold mt-1">Simpan yang paling cocok</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-3 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-white/65">Langkah 3</p>
                        <p class="font-semibold mt-1">Lamar dan pantau progres</p>
                    </div>
                </div>

                <!-- Advanced Search Form -->
                <form action="{{ route('jobs.index') }}" method="GET" class="max-w-5xl">
                    <div class="bg-white/95 backdrop-blur rounded-3xl shadow-xl ring-1 ring-white/30 border border-white/20 p-5 md:p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Filter pencarian</p>
                                <p class="text-sm text-slate-500 mt-1">Gunakan kata kunci, lokasi, dan jenis pekerjaan untuk memperkecil hasil.</p>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                                <span class="px-3 py-1 rounded-full bg-slate-100">Tips: coba judul posisi</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label class="ui-label text-slate-700">Kata Kunci</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           placeholder="Judul pekerjaan, posisi, perusahaan..."
                                           class="ui-input pl-10">
                                    <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="ui-label text-slate-700">Lokasi</label>
                                <select name="location" class="ui-select">
                                    <option value="">Semua Lokasi</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Job Type -->
                            <div>
                                <label class="ui-label text-slate-700">Jenis Pekerjaan</label>
                                <select name="job_type" class="ui-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="full_time" {{ request('job_type') == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                    <option value="part_time" {{ request('job_type') == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                    <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                                    <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-6">
                            <a href="{{ route('jobs.index') }}" class="text-sm text-slate-500 hover:text-slate-800 font-medium inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M10 18h4"/></svg>
                                Hapus Filter
                            </a>
                            <x-ui.btn type="submit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari Lowongan
                            </x-ui.btn>
                        </div>
                    </div>
                </form>
        </x-slot:extra>
    </x-ui.page-hero>

        <div class="page-container page-section">
            <!-- Results Header -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Posisi Tersedia</h2>
                    <p class="text-gray-600 mt-1">Menampilkan {{ $jobs->firstItem() ?? 0 }}-{{ $jobs->lastItem() ?? 0 }} dari {{ $jobs->total() }} lowongan.</p>
                    <p class="text-sm text-gray-500 mt-1">Buka detail lowongan dulu supaya kamu bisa cek kualifikasi dan lokasi dengan lebih tenang.</p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700">Urutkan:</label>
                    <select name="sort" onchange="window.location.href = updateQueryParam('sort', this.value)" class="py-2 px-4 rounded-xl border border-gray-200 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Gaji tertinggi</option>
                        <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Gaji terendah</option>
                        <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Tenggat waktu</option>
                    </select>
                </div>
            </div>

            <!-- Job Cards Grid -->
            @if($jobs->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @foreach($jobs as $job)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200 overflow-hidden group">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <!-- Company Logo -->
                            <div class="flex-shrink-0">
                                @if($job->company->user->avatar ?? null)
                                <img src="{{ asset('storage/' . $job->company->user->avatar) }}" 
                                     alt="{{ $job->company->name }}" 
                                     class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-md">
                                @else
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ substr($job->company->name ?? 'C', 0, 1) }}
                                </div>
                                @endif
                            </div>

                            <!-- Job Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1 leading-tight">
                                    <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
                                    </h3>
                                </div>
                                <p class="text-gray-600 font-medium mb-3">{{ $job->company->name ?? __('bkk.fallback.company') }}</p>
                                
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    <!-- Location -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $job->location }}
                                    </span>

                                    <!-- Job Type -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \App\Support\Label::jobType($job->job_type) }}
                                    </span>

                                    <!-- Deadline -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $job->deadline->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Salary -->
                                @if($job->salary_min && $job->salary_max)
                                <div class="flex items-center justify-between gap-3 mb-4 rounded-2xl bg-emerald-50/80 border border-emerald-100 px-4 py-3">
                                    <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Estimasi gaji</span>
                                    </div>
                                    <span class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                                    </span>
                                </div>
                                @endif

                                <!-- Description Preview -->
                                <p class="text-sm text-gray-600 line-clamp-3 mb-5 leading-relaxed">
                                    {{ Str::limit(strip_tags($job->description), 120) }}
                                </p>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('jobs.show', $job->id) }}" 
                                       class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition text-center shadow-sm">
                                        Lihat Detail
                                    </a>
                                    
                                    @auth
                                    <button onclick="toggleBookmark({{ $job->id }})" 
                                            class="p-2.5 border border-gray-200 rounded-xl hover:border-red-500 hover:bg-red-50 transition-colors bookmark-btn-{{ $job->id }}">
                                        <svg class="w-5 h-5 text-gray-600 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Stats -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-500">
                                Diposting {{ $job->created_at->diffForHumans() }}
                            </span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $job->applications_count ?? 0 }} pelamar
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
            @else
            <!-- Empty State -->
            <x-ui.panel>
                <x-ui.empty-state
                    title="Tidak ada lowongan ditemukan"
                    description="Coba ubah kata kunci, lokasi, atau jenis pekerjaan. Jika hasil masih kosong, berarti belum ada lowongan yang cocok dengan filtermu saat ini."
                >
                    <x-slot:action>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <x-ui.btn href="{{ route('jobs.index') }}">Hapus Semua Filter</x-ui.btn>
                            <x-ui.btn variant="secondary" href="{{ route('dashboard') }}">Kembali ke Dasbor</x-ui.btn>
                        </div>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, value);
            return url.toString();
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
                const btn = document.querySelector(`.bookmark-btn-${jobId} svg`);
                if (data.bookmarked) {
                    btn.setAttribute('fill', 'currentColor');
                    btn.classList.add('text-red-500');
                } else {
                    btn.setAttribute('fill', 'none');
                    btn.classList.remove('text-red-500');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
