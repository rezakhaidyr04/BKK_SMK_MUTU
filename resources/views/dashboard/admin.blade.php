<x-app-layout :full-bleed="true">
    <div class="page-shell">
    <!-- Admin Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Dasbor Admin</h1>
                        <p class="text-blue-100">Ringkasan lengkap sistem dan analitik</p>
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
            <!-- Main Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Students Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        @if($growth['students'] >= 0)
                            <span class="text-green-600 font-semibold">+{{ $growth['students'] }}%</span>
                        @else
                            <span class="text-red-500 font-semibold">{{ $growth['students'] }}%</span>
                        @endif
                        <span class="text-gray-600 ml-2">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Alumni Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Alumni</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_alumni'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        @if($growth['alumni'] >= 0)
                            <span class="text-green-600 font-semibold">+{{ $growth['alumni'] }}%</span>
                        @else
                            <span class="text-red-500 font-semibold">{{ $growth['alumni'] }}%</span>
                        @endif
                        <span class="text-gray-600 ml-2">dari bulan lalu</span>
                    </div>
                </div>

                <!-- Companies Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Perusahaan</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_companies'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+{{ $growth['companies_new'] }}</span>
                        <span class="text-gray-600 ml-2">baru bulan ini</span>
                    </div>
                </div>

                <!-- Jobs Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Lowongan Aktif</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_jobs'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+{{ $growth['jobs_new'] }}</span>
                        <span class="text-gray-600 ml-2">posting baru bulan ini</span>
                    </div>
                </div>
            </div>

            <!-- Application Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Lamaran</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Menunggu Tinjauan</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['interviews_scheduled'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Wawancara</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['accepted_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Diterima</p>
                </div>
            </div>

            <!-- Charts & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Application Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tren Lamaran (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="applicationChart"></canvas>
                    </div>
                </div>

                <!-- Job Posting Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Tren Posting Lowongan (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="jobChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Applications & Top Companies -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Applications -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Lamaran Terbaru</h3>
                    <div class="space-y-4">
                        @foreach($recentApplications->take(5) as $app)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 font-bold text-lg">{{ substr($app->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $app->user->name }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ $app->job->title }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                {{ \App\Support\Label::applicationStatus($app->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Companies -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Perusahaan Teratas</h3>
                    <div class="space-y-4">
                        @foreach($topCompanies as $company)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ substr($company->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $company->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $company->job_count }} lowongan diposting</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    // Application chart data dari controller
    const appLabels = @json($applicationChart->pluck('month'));
    const appData   = @json($applicationChart->pluck('count'));

    // Job chart data dari controller
    const jobLabels = @json($jobChart->pluck('month'));
    const jobData   = @json($jobChart->pluck('count'));

    // Format label "YYYY-MM" -> "Jan 2025"
    function formatMonth(ym) {
        if (!ym) return '';
        const [y, m] = ym.split('-');
        const names = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return names[parseInt(m)] + ' ' + y;
    }

    new Chart(document.getElementById('applicationChart'), {
        type: 'bar',
        data: {
            labels: appLabels.map(formatMonth),
            datasets: [{
                label: 'Jumlah Lamaran',
                data: appData,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    new Chart(document.getElementById('jobChart'), {
        type: 'line',
        data: {
            labels: jobLabels.map(formatMonth),
            datasets: [{
                label: 'Lowongan Diposting',
                data: jobData,
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
})();
</script>
@endpush
</x-app-layout>
