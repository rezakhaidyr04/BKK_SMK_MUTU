<x-app-layout :full-bleed="true" title="Lowongan Kerja — BKK SMK MUTU" description="Daftar lowongan kerja terbaru dari perusahaan mitra BKK SMK MUTU. Temukan peluang karier untuk siswa dan alumni.">
    <div class="page-shell">
    <!-- Hero Search Section -->
    <section class="jobs-search-hero border-b border-slate-200">
        <div class="page-container">
            <div class="w-full">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white border border-white/20 mb-3">
                    Lowongan aktif · Filter cepat · Siap dilamar
                </div>
                <h1 class="text-3xl font-bold text-white">Temukan Pekerjaan Impian Anda</h1>
                <p class="mt-2 text-blue-100 max-w-2xl">Temukan {{ $jobs->total() }} peluang yang menunggu Anda, lalu saring hasilnya agar lebih sesuai dengan posisi, lokasi, dan jenis pekerjaan yang dicari.</p>
            </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 mt-5">
                    <div class="bg-white/10 border border-white/20 rounded-xl p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-blue-200 font-semibold">Langkah 1</p>
                        <p class="text-white font-semibold mt-1">Cari lowongan yang relevan</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-xl p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-blue-200 font-semibold">Langkah 2</p>
                        <p class="text-white font-semibold mt-1">Simpan yang paling cocok</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-xl p-4 backdrop-blur-sm">
                        <p class="text-xs uppercase tracking-[0.18em] text-blue-200 font-semibold">Langkah 3</p>
                        <p class="text-white font-semibold mt-1">Lamar dan pantau progres</p>
                    </div>
                </div>

                <!-- Advanced Search Form -->
                <form action="{{ route('jobs.index') }}" method="GET" class="w-full">
                    <div class="bg-white shadow-lg border border-slate-100 rounded-2xl p-5 md:p-6 mt-2">
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
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           placeholder="Judul pekerjaan, posisi, perusahaan..."
                                           class="ui-input border-slate-300 focus:border-blue-500 focus:ring-blue-500 pl-8">
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="ui-label text-slate-700">Lokasi</label>
                                <select name="location" class="ui-select border-slate-300 focus:border-blue-500 focus:ring-blue-500">
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
                                <select name="job_type" class="ui-select border-slate-300 focus:border-blue-500 focus:ring-blue-500">
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
                            <x-ui.btn type="submit" class="bg-blue-600 hover:bg-blue-700 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari Lowongan
                            </x-ui.btn>
                        </div>
                    </div>
                </form>
        </div>
    </section>

        <div class="page-container page-section">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl px-5 py-4 text-white shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-200">Lowongan Aktif</p>
                    <p class="mt-1 text-3xl font-bold">{{ $activeJobsCount }}</p>
                </div>
                <div class="bg-gradient-to-br from-cyan-600 to-cyan-700 rounded-2xl px-5 py-4 text-white shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-100">Lokasi</p>
                    <p class="mt-1 text-3xl font-bold">{{ $locationsCount }}</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl px-5 py-4 text-white shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-200">Perusahaan</p>
                    <p class="mt-1 text-3xl font-bold">{{ $companiesCount }}</p>
                </div>
            </div>

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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8 items-stretch">
                @foreach($jobs as $job)
                <div class="h-full bg-white rounded-2xl shadow-sm transition-all duration-200 border border-slate-200 hover:border-slate-300 overflow-hidden group">
                    <div class="p-4 sm:p-5 h-full flex flex-col">
                        <div class="flex items-start justify-between gap-3 sm:gap-4 mb-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    @php
                                        $jobTypeClasses = match($job->job_type) {
                                            'full_time' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                            'part_time' => 'bg-violet-50 text-violet-700 border border-violet-100',
                                            'internship' => 'bg-green-50 text-green-700 border border-green-100',
                                            'contract' => 'bg-amber-50 text-amber-700 border border-amber-100',
                                            default => 'bg-slate-100 text-slate-600 border border-slate-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-medium uppercase tracking-wide {{ $jobTypeClasses }}">{{ \App\Support\Label::jobType($job->job_type) }}</span>
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-[11px] font-medium text-slate-600 border border-slate-200">{{ $job->location }}</span>
                                    @if($job->created_at->gte(now()->subDays(7)))
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[11px] font-medium text-green-700 border border-green-100">Baru</span>
                                    @endif
                                    @if($job->deadline->lte(now()->addDays(3)))
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-medium text-amber-700 border border-amber-100">Deadline dekat</span>
                                    @endif
                                </div>
                                <h3 class="text-lg sm:text-[1.15rem] font-semibold text-slate-900 transition-colors leading-tight">
                                    <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
                                </h3>
                                <p class="text-sm text-slate-600 font-medium mt-1.5">{{ $job->company_name ?? 'Perusahaan' }}</p>
                            </div>
                            <div class="shrink-0 rounded-xl bg-slate-100 px-3 py-2 sm:px-3.5 sm:py-2.5 text-slate-700 text-sm sm:text-base font-semibold border border-slate-200">
                                {{ substr($job->company_name ?? 'C', 0, 1) }}
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Deadline {{ $job->deadline->diffForHumans() }}
                            </span>
                        </div>

                        @if($job->salary_min && $job->salary_max)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 mb-3 rounded-xl bg-slate-50 border border-slate-200 px-3.5 py-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-medium text-slate-500 uppercase tracking-[0.14em]">Estimasi gaji</span>
                            </div>
                            <span class="text-base sm:text-lg font-semibold text-slate-900">
                                Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                            </span>
                        </div>
                        @endif

                        <p class="text-sm text-slate-600 line-clamp-2 mb-4 leading-relaxed min-h-[2.75rem]">
                            {{ Str::limit(strip_tags($job->description), 120) }}
                        </p>

                        <div class="flex items-center gap-3 mt-auto">
                            <a href="{{ route('jobs.show', $job->id) }}" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition text-center">
                                Lihat Detail
                            </a>

                            @auth
                            <button onclick="toggleBookmark({{ $job->id }})" aria-label="Simpan lowongan" class="p-2.5 border border-slate-200 rounded-lg hover:border-red-200 hover:bg-red-50 transition-colors bookmark-btn-{{ $job->id }}">
                                <svg class="w-5 h-5 text-slate-500 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                            @endauth
                        </div>

                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100 text-sm">
                            <span class="text-slate-500">
                                Diposting {{ $job->created_at->diffForHumans() }}
                            </span>
                            <span class="font-medium text-slate-700">
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

        async function toggleBookmark(jobId) {
            const btn = document.querySelector(`.bookmark-btn-${jobId}`);
            if (!btn || btn.dataset.busy === '1') return;
            btn.dataset.busy = '1';
            btn.disabled = true;
            btn.style.opacity = '0.6';

            try {
                const response = await fetch(`/jobs/${jobId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Gagal menyimpan');

                const data = await response.json();
                const svg = btn.querySelector('svg');
                if (data.bookmarked) {
                    svg.setAttribute('fill', 'currentColor');
                    svg.classList.add('text-red-500');
                } else {
                    svg.setAttribute('fill', 'none');
                    svg.classList.remove('text-red-500');
                }

                if (window.toast && data.message) {
                    window.toast.success(data.message);
                }
            } catch (error) {
                if (window.toast) {
                    window.toast.error('Gagal memperbarui simpanan. Coba lagi.');
                } else {
                    alert('Gagal memperbarui simpanan. Coba lagi.');
                }
            } finally {
                btn.dataset.busy = '0';
                btn.disabled = false;
                btn.style.opacity = '';
            }
        }
    </script>
    @endpush
</x-app-layout>
