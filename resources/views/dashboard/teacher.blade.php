<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-2xl font-bold text-white">Dasbor Guru</h1>
                <p class="text-blue-100 mt-1 text-sm">Monitoring penempatan kerja siswa & alumni</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Alumni</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_alumni'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Sudah Ditempatkan</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['placed_students'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lowongan Aktif</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['active_jobs'] ?? 0 }}</p>
                    <a href="{{ route('jobs.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Lihat →</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Penempatan Terbaru --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Penempatan Terbaru</h2>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($recentPlacements->take(10) as $app)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-700 font-semibold">
                                {{ substr($app->user->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $app->job->title ?? 'Lowongan' }} · {{ $app->job->company->name ?? '' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Diterima</span>
                                <p class="text-xs text-gray-400 mt-1">{{ $app->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-10 text-center text-gray-400 text-sm">
                            Belum ada penempatan terbaru.
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar: Status Penempatan --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Status Lamaran</h3>
                        </div>
                        <div class="p-6">
                            @forelse($placementData as $p)
                            @php
                                $statusLabel = [
                                    'submitted'    => 'Terkirim',
                                    'under_review' => 'Ditinjau',
                                    'interviewed'  => 'Interview',
                                    'accepted'     => 'Diterima',
                                    'rejected'     => 'Ditolak',
                                ][$p->status] ?? $p->status;
                                $total = $placementData->sum('count');
                                $pct = $total > 0 ? round(($p->count / $total) * 100) : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 font-medium">{{ $statusLabel }}</span>
                                    <span class="text-gray-900 font-bold">{{ $p->count }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-400 text-sm text-center py-4">Belum ada data.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Link cepat --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="font-semibold text-gray-900 mb-3">Informasi</h3>
                        <div class="space-y-2">
                            <a href="{{ route('jobs.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Lihat Semua Lowongan
                            </a>
                            <a href="{{ route('events.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Acara Karir
                            </a>
                            <a href="{{ route('news.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
