<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-teal-600 to-green-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Dasbor Guru</h1>
                        <p class="text-blue-100">Monitoring penempatan kerja siswa & alumni</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="flex items-center gap-3">
                            <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white">
                                <p class="text-sm">Hari Ini</p>
                                <p class="font-semibold">{{ now()->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">Terdaftar di sistem</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Alumni</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_alumni'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">Sudah lulus</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Sudah Ditempatkan</p>
                            <p class="text-4xl font-bold text-green-600">{{ $stats['placed_students'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">Lamaran diterima</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Lowongan Aktif</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['active_jobs'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('jobs.index') }}" class="text-sm text-orange-600 font-semibold hover:underline">Lihat semua →</a>
                    </div>
                </div>
            </div>

            <!-- Status Lamaran Summary -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                @php
                    $statusConfig = [
                        'submitted'    => ['label' => 'Terkirim',    'color' => 'text-blue-600',   'bg' => 'bg-blue-50'],
                        'under_review' => ['label' => 'Ditinjau',    'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
                        'interviewed'  => ['label' => 'Interview',   'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                        'accepted'     => ['label' => 'Diterima',    'color' => 'text-green-600',  'bg' => 'bg-green-50'],
                        'rejected'     => ['label' => 'Ditolak',     'color' => 'text-red-600',    'bg' => 'bg-red-50'],
                    ];
                @endphp
                @foreach($statusConfig as $key => $cfg)
                    @php $item = $placementData->firstWhere('status', $key); @endphp
                    <div class="bg-white rounded-xl shadow-md p-5 text-center">
                        <p class="text-3xl font-bold {{ $cfg['color'] }}">{{ $item->count ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $cfg['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Penempatan Terbaru -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Penempatan Terbaru</h3>
                    <div class="space-y-4">
                        @forelse($recentPlacements->take(8) as $app)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-green-700 font-bold text-lg">{{ substr($app->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ $app->job->title ?? 'Lowongan' }} · {{ $app->job->company->name ?? '' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Diterima</span>
                                <p class="text-xs text-gray-400 mt-1">{{ $app->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada penempatan terbaru.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Status Progress & Info -->
                <div class="space-y-6">
                    <!-- Progress Bar Status -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Distribusi Status Lamaran</h3>
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
                                <span class="text-gray-700 font-medium">{{ $statusLabel }}</span>
                                <span class="text-gray-900 font-bold">{{ $p->count }} <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="{{ $bar }} h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-400 text-sm text-center py-4">Belum ada data.</p>
                        @endforelse
                    </div>

                    <!-- Aksi Cepat -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Lihat Semua Lowongan
                            </a>
                            <a href="{{ route('events.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Acara Karir
                            </a>
                            <a href="{{ route('news.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                Berita Karir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
