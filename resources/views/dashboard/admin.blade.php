<x-app-layout :full-bleed="true">
    <div class="page-shell">
    {{-- Admin Header --}}
    <x-ui.dashboard-hero
        title="Dasbor Admin"
        subtitle="Ringkasan lengkap sistem dan analitik"
        gradient="from-indigo-600 via-purple-600 to-pink-600"
    />

        <div class="page-container page-section">
            {{-- Main Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.dashboard-stat-card
                    label="Total Siswa"
                    :value="$stats['total_students']"
                    color="blue"
                    class="animate-slide-up animate-slide-up-1"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </x-slot:icon>
                    <x-slot:footer>
                        <div class="flex items-center text-sm">
                            @if($growth['students'] >= 0)
                                <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $growth['students'] }}%</span>
                            @else
                                <span class="text-red-500 dark:text-red-400 font-semibold">{{ $growth['students'] }}%</span>
                            @endif
                            <span class="text-gray-600 dark:text-gray-400 ml-2">dari bulan lalu</span>
                        </div>
                    </x-slot:footer>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Total Alumni"
                    :value="$stats['total_alumni']"
                    color="green"
                    class="animate-slide-up animate-slide-up-2"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </x-slot:icon>
                    <x-slot:footer>
                        <div class="flex items-center text-sm">
                            @if($growth['alumni'] >= 0)
                                <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $growth['alumni'] }}%</span>
                            @else
                                <span class="text-red-500 dark:text-red-400 font-semibold">{{ $growth['alumni'] }}%</span>
                            @endif
                            <span class="text-gray-600 dark:text-gray-400 ml-2">dari bulan lalu</span>
                        </div>
                    </x-slot:footer>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Perusahaan"
                    :value="$stats['total_companies']"
                    color="purple"
                    class="animate-slide-up animate-slide-up-3"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </x-slot:icon>
                    <x-slot:footer>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $growth['companies_new'] }}</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">baru bulan ini</span>
                        </div>
                    </x-slot:footer>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Lowongan Aktif"
                    :value="$stats['total_jobs']"
                    color="orange"
                    class="animate-slide-up animate-slide-up-4"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </x-slot:icon>
                    <x-slot:footer>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 dark:text-green-400 font-semibold">+{{ $growth['jobs_new'] }}</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">posting baru bulan ini</span>
                        </div>
                    </x-slot:footer>
                </x-ui.dashboard-stat-card>
            </div>

            {{-- Application Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 animate-slide-up animate-slide-up-1">
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_applications'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Lamaran</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 animate-slide-up animate-slide-up-2">
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_applications'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Menunggu Tinjauan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 animate-slide-up animate-slide-up-3">
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['interviews_scheduled'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Wawancara</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 animate-slide-up animate-slide-up-4">
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['accepted_applications'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Diterima</p>
                </div>
            </div>

            {{-- Charts & Recent Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tren Lamaran (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="applicationChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tren Posting Lowongan (6 Bulan Terakhir)</h3>
                    <div class="h-64">
                        <canvas id="jobChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Recent Applications & Top Companies --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Lamaran Terbaru</h3>
                    <div class="space-y-4">
                        @foreach($recentApplications->take(5) as $app)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                <span class="text-blue-600 dark:text-blue-400 font-bold text-lg">{{ substr($app->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $app->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $app->job->title }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">
                                {{ \App\Support\Label::applicationStatus($app->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Perusahaan Teratas</h3>
                    <div class="space-y-4">
                        @foreach($topCompanies as $company)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 hover:-translate-y-0.5 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold" aria-hidden="true">
                                    {{ substr($company->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $company->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company->job_count }} lowongan diposting</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(0, 0, 0, 0.05)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    const appLabels = @json($applicationChart->pluck('month'));
    const appData   = @json($applicationChart->pluck('count'));
    const jobLabels = @json($jobChart->pluck('month'));
    const jobData   = @json($jobChart->pluck('count'));

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
                backgroundColor: isDark ? 'rgba(96, 165, 250, 0.6)' : 'rgba(59, 130, 246, 0.7)',
                borderColor: isDark ? 'rgba(96, 165, 250, 1)' : 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor } },
                x: { ticks: { color: textColor }, grid: { color: gridColor } }
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
                borderColor: isDark ? 'rgba(52, 211, 153, 1)' : 'rgba(16, 185, 129, 1)',
                backgroundColor: isDark ? 'rgba(52, 211, 153, 0.1)' : 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: isDark ? 'rgba(52, 211, 153, 1)' : 'rgba(16, 185, 129, 1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor } },
                x: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });
})();
</script>
@endpush
</x-app-layout>
