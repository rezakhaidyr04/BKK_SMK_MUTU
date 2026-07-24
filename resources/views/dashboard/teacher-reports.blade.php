<x-app-layout>
    <div class="page-shell">
        <x-ui.dashboard-hero
            title="Laporan & Statistik"
            subtitle="Analisis data penempatan dan performa siswa"
            gradient="from-yellow-600 via-orange-600 to-red-600"
        />

        <div class="page-container page-section">
            {{-- Overview Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_students'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Siswa Ditempatkan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_placed'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tingkat Penempatan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['placement_rate'] }}%</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4m6 2l4-4m6 8H3m2 0h6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Lowongan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_jobs'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Placement by Major --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Penempatan per Jurusan</h3>
                    <div class="space-y-4">
                        @forelse($placementByMajor as $data)
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $data['major'] }}</span>
                                <span class="text-gray-900 dark:text-white font-bold">{{ $data['placed'] }} / {{ $data['total'] }} ({{ $data['percentage'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-teal-500 to-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $data['percentage'] }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Belum ada data penempatan per jurusan</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Application Status Distribution --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Distribusi Status Lamaran</h3>
                    <div class="space-y-4">
                        @php
                            $statusLabels = [
                                'submitted' => 'Terkirim',
                                'under_review' => 'Ditinjau',
                                'interviewed' => 'Interview',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                            ];
                            $statusColors = [
                                'submitted' => 'bg-blue-500',
                                'under_review' => 'bg-yellow-500',
                                'interviewed' => 'bg-purple-500',
                                'accepted' => 'bg-green-500',
                                'rejected' => 'bg-red-500',
                            ];
                            $totalStatus = $statusDistribution->sum();
                        @endphp
                        @foreach($statusLabels as $key => $label)
                        @php
                            $count = $statusDistribution->get($key, 0);
                            $percentage = $totalStatus > 0 ? round(($count / $totalStatus) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $label }}</span>
                                <span class="text-gray-900 dark:text-white font-bold">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $statusColors[$key] }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Top Companies --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Perusahaan Teratas</h3>
                    <div class="space-y-4">
                        @forelse($topCompanies as $company)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ substr($company->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $company->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company->count }} siswa ditempatkan</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Belum ada data perusahaan</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Monthly Trends --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Tren Penempatan Bulanan</h3>
                    <div class="space-y-4">
                        @forelse($monthlyTrends as $trend)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($trend->month)->format('F Y') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $trend->count }} penempatan</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4m6 2l4-4m6 8H3m2 0h6"/>
                                </svg>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>Belum ada data tren bulanan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
