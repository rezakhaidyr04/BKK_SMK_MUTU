<x-app-layout :full-bleed="true">
    <div class="page-shell">
        {{-- Hero Header (Reusable Component) --}}
        <x-ui.dashboard-hero
            title="Dasbor Guru"
            subtitle="Monitoring penempatan kerja siswa & alumni"
            gradient="from-blue-600 via-teal-600 to-green-600"
        />

        <div class="page-container pt-6">
            <div class="rounded-2xl border border-teal-100 bg-teal-50 px-5 py-4 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-teal-800">Fokus utama guru</p>
                        <p class="text-sm text-teal-700 mt-1">Pantau penempatan, lihat distribusi status, dan awasi siswa/alumni yang sudah terserap kerja.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs font-semibold text-teal-700">
                        <span class="px-3 py-1 rounded-full bg-white border border-teal-100">Penempatan</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-teal-100">Status lamaran</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-teal-100">Monitoring</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('teacher.students.index') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white text-center">Lihat Siswa</span>
                    </a>
                    
                    <a href="{{ route('teacher.placements.index') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white text-center">Penempatan</span>
                    </a>
                    
                    <a href="{{ route('teacher.reports.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors group">
                        <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white text-center">Laporan</span>
                    </a>
                    
                    <a href="{{ route('teacher.events.index') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white text-center">Acara</span>
                    </a>
                </div>
            </div>

            {{-- Stats Grid (Refactored using reusable component) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.dashboard-stat-card
                    label="Total Siswa"
                    :value="$stats['total_students'] ?? 0"
                    color="blue"
                    footnote="Terdaftar di sistem"
                    class="animate-slide-up animate-slide-up-1"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Total Alumni"
                    :value="$stats['total_alumni'] ?? 0"
                    color="green"
                    footnote="Sudah lulus"
                    class="animate-slide-up animate-slide-up-2"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Sudah Ditempatkan"
                    :value="$stats['placed_students'] ?? 0"
                    color="purple"
                    footnote="Lamaran diterima"
                    class="animate-slide-up animate-slide-up-3"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Lowongan Aktif"
                    :value="$stats['active_jobs'] ?? 0"
                    color="orange"
                    :href="route('jobs.index')"
                    hrefLabel="Lihat semua →"
                    class="animate-slide-up animate-slide-up-4"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>
            </div>

            {{-- Status Lamaran Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                @php
                    $statusConfig = [
                        'submitted'    => ['label' => 'Terkirim',    'color' => 'text-blue-600 dark:text-blue-400',   'bg' => 'bg-blue-50 dark:bg-blue-900/20'],
                        'under_review' => ['label' => 'Ditinjau',    'color' => 'text-yellow-600 dark:text-yellow-400', 'bg' => 'bg-yellow-50 dark:bg-yellow-900/20'],
                        'interviewed'  => ['label' => 'Interview',   'color' => 'text-purple-600 dark:text-purple-400', 'bg' => 'bg-purple-50 dark:bg-purple-900/20'],
                        'accepted'     => ['label' => 'Diterima',    'color' => 'text-green-600 dark:text-green-400',  'bg' => 'bg-green-50 dark:bg-green-900/20'],
                        'rejected'     => ['label' => 'Ditolak',     'color' => 'text-red-600 dark:text-red-400',    'bg' => 'bg-red-50 dark:bg-red-900/20'],
                    ];
                @endphp
                @foreach($statusConfig as $key => $cfg)
                    @php $item = $placementData->firstWhere('status', $key); @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 animate-slide-up animate-slide-up-{{ $loop->iteration }}">
                        <p class="text-3xl font-bold {{ $cfg['color'] }}">{{ $item->count ?? 0 }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $cfg['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ 
                placementsOpen: true,
                statusOpen: true
            }">
                {{-- Penempatan Terbaru --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/20 dark:to-teal-900/20 cursor-pointer" @click="placementsOpen = !placementsOpen">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Penempatan Terbaru</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Siswa yang sudah ditempatkan</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <select id="filterYear" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500" @click.stop>
                                        <option value="">Semua Tahun</option>
                                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    <select id="filterMajor" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500" @click.stop>
                                        <option value="">Semua Jurusan</option>
                                        <option value="TKJ">Teknik Komputer & Jaringan</option>
                                        <option value="RPL">Rekayasa Perangkat Lunak</option>
                                        <option value="MM">Multimedia</option>
                                        <option value="AKL">Akuntansi</option>
                                        <option value="OTKP">Otomatisasi & Tata Kelola Perkantoran</option>
                                    </select>
                                </div>
                                <svg x-show="placementsOpen" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <svg x-show="!placementsOpen" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div x-show="placementsOpen" x-collapse class="p-6">
                    <div class="space-y-4">
                        @forelse($recentPlacements->take(8) as $app)
                        @php $studentId = $app->user->student_id ?? null; @endphp
                        @if($studentId)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" onclick="window.location.href='{{ route('teacher.student.show', $studentId) }}'">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                <span class="text-green-700 dark:text-green-400 font-bold text-lg">{{ substr($app->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $app->job->title ?? 'Lowongan' }} · {{ $app->job->company->name ?? '' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">Diterima</span>
                                <p class="text-xs text-gray-400 mt-1">{{ $app->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                <span class="text-green-700 dark:text-green-400 font-bold text-lg">{{ substr($app->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $app->job->title ?? 'Lowongan' }} · {{ $app->job->company->name ?? '' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">Diterima</span>
                                <p class="text-xs text-gray-400 mt-1">{{ $app->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endif
                        @empty
                        {{-- Enhanced Empty State --}}
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-100 to-teal-100 dark:from-green-900/30 dark:to-teal-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                <svg class="w-10 h-10 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Penempatan</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs mx-auto">Data penempatan siswa dan alumni akan muncul di sini setelah ada lamaran yang diterima.</p>
                        </div>
                        @endforelse
                    </div>
                    </div>
                </div>

                {{-- Status Progress & Info --}}
                <div class="space-y-6">
                    {{-- Progress Bar Status --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 cursor-pointer" @click="statusOpen = !statusOpen">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Distribusi Status Lamaran</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Statistik status lamaran siswa</p>
                                    </div>
                                </div>
                                <svg x-show="statusOpen" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <svg x-show="!statusOpen" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div x-show="statusOpen" x-collapse class="p-6">
                        @php $total = $placementData->sum('count'); @endphp
                        @forelse($placementData as $p)
                        @php
                            $statusLabel = [
                                'submitted'    => 'Terkirim',
                                'under_review' => 'Ditinjau',
                                'interviewed'  => 'Interview',
                                'accepted'     => 'Diterima',
                                'rejected'     => 'Ditolak',
                            ][$p->status] ?? $p->status;
                            $colors = [
                                'submitted'    => 'bg-blue-500',
                                'under_review' => 'bg-yellow-500',
                                'interviewed'  => 'bg-purple-500',
                                'accepted'     => 'bg-green-500',
                                'rejected'     => 'bg-red-500',
                            ];
                            $bar = $colors[$p->status] ?? 'bg-gray-500';
                            $pct = $total > 0 ? round(($p->count / $total) * 100) : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $statusLabel }}</span>
                                <span class="text-gray-900 dark:text-white font-bold">{{ $p->count }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                <div class="ui-progress-fill {{ $bar }} h-2.5 rounded-full" style="--progress: {{ $pct }}%;"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada data distribusi.</p>
                        </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
