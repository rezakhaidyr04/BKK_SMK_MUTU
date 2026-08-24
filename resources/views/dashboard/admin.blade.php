<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Dasbor Admin" subtitle="Ringkasan lengkap sistem dan analitik." />
    </x-slot>

        <div class="pt-2">
            <div class="rounded-2xl border border-indigo-100 bg-indigo-50 px-5 py-4 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-indigo-800">Fokus utama admin</p>
                        <p class="text-sm text-indigo-700 mt-1">Pantau data, verifikasi perusahaan, moderasi konten, dan jaga sistem tetap rapi.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs font-semibold text-indigo-700">
                        <span class="px-3 py-1 rounded-full bg-white border border-indigo-100">Verifikasi</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-indigo-100">Analitik</span>
                        <span class="px-3 py-1 rounded-full bg-white border border-indigo-100">Manajemen user</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-100 dark:border-neutral-800 p-6 mb-8" data-reveal>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50">Aksi Cepat</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Pintasan untuk tugas umum</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3" data-stagger>
                    <a href="{{ route('admin.jobs.create') }}" class="group flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-all duration-200 hover:-translate-y-0.5 border border-transparent hover:border-primary-200 dark:hover:border-primary-800">
                        <div class="w-10 h-10 bg-primary-600 dark:bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-50">Posting Lowongan</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Buat lowongan baru</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 p-4 bg-success-50 dark:bg-success-900/20 rounded-xl hover:bg-success-100 dark:hover:bg-success-900/30 transition-all duration-200 hover:-translate-y-0.5 border border-transparent hover:border-success-200 dark:hover:border-success-800">
                        <div class="w-10 h-10 bg-success-600 dark:bg-success-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-50">Kelola User</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Manajemen pengguna</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="group flex items-center gap-3 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-all duration-200 hover:-translate-y-0.5 border border-transparent hover:border-purple-200 dark:hover:border-purple-800">
                        <div class="w-10 h-10 bg-purple-600 dark:bg-purple-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200 shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-50">Laporan</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Ekspor & analitik</p>
                        </div>
                    </a>
                </div>
            </div>

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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8" data-stagger>
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 shadow-sm p-5 text-center hover:-translate-y-1 hover:shadow-md transition-all duration-300" data-reveal>
                    <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $stats['total_applications'] }}</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1 font-medium">Total Lamaran</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 shadow-sm p-5 text-center hover:-translate-y-1 hover:shadow-md transition-all duration-300" data-reveal>
                    <p class="text-3xl font-bold text-warning-600 dark:text-warning-400">{{ $stats['pending_applications'] }}</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1 font-medium">Menunggu Tinjauan</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 shadow-sm p-5 text-center hover:-translate-y-1 hover:shadow-md transition-all duration-300" data-reveal>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['interviews_scheduled'] }}</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1 font-medium">Wawancara</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 shadow-sm p-5 text-center hover:-translate-y-1 hover:shadow-md transition-all duration-300" data-reveal>
                    <p class="text-3xl font-bold text-success-600 dark:text-success-400">{{ $stats['accepted_applications'] }}</p>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1 font-medium">Diterima</p>
                </div>
            </div>

            {{-- Charts & Recent Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8" x-data="{
                chartsLoaded: false,
                chartsOpen: true,
                statusOpen: true
            }" x-init="$nextTick(() => { chartsLoaded = true; setTimeout(() => initCharts(), 150); })">
                <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-100 dark:border-neutral-800 overflow-hidden" data-reveal>
                    <div class="px-6 py-5 border-b border-neutral-100 dark:border-neutral-800 cursor-pointer select-none" @click="chartsOpen = !chartsOpen">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50">Tren Lamaran & Lowongan</h3>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Statistik 6 bulan terakhir</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-neutral-400 transition-transform duration-200" :class="chartsOpen ? 'rotate-0' : '-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div x-show="chartsOpen" x-collapse class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Tren Lamaran</h4>
                                <div class="h-48">
                                    <canvas id="applicationChart"></canvas>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Tren Lowongan</h4>
                                <div class="h-48">
                                    <canvas id="jobChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-100 dark:border-neutral-800 overflow-hidden" data-reveal>
                    <div class="px-6 py-5 border-b border-neutral-100 dark:border-neutral-800 cursor-pointer select-none" @click="statusOpen = !statusOpen">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-success-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50">Sebaran Status & Role</h3>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Distribusi data sistem</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-neutral-400 transition-transform duration-200" :class="statusOpen ? 'rotate-0' : '-rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div x-show="statusOpen" x-collapse class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Status Lamaran</h4>
                                <div class="h-48">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Role Pengguna</h4>
                                <div class="h-48">
                                    <canvas id="userRoleChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Applications & Top Companies --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ 
                applicationsOpen: true,
                companiesOpen: true
            }">
                <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-neutral-100 dark:border-neutral-800 overflow-hidden" data-reveal>
                    <div class="px-6 py-5 border-b border-neutral-100 dark:border-neutral-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50">Lamaran Terbaru</h3>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Aktivitas lamaran terbaru</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                    <div class="space-y-3">
                        @forelse($recentApplications->take(5) as $app)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:-translate-y-0.5 transition-all duration-200 cursor-default">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                <span class="text-primary-600 dark:text-primary-400 font-bold text-sm">{{ substr($app->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-neutral-900 dark:text-neutral-50 truncate">{{ $app->user->name }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ $app->job?->title ?? 'Pekerjaan Dihapus' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-xs font-semibold rounded-full shrink-0">
                                {{ \App\Support\Label::applicationStatus($app->status) }}
                            </span>
                        </div>
                        @empty
                        <x-ui.empty-state
                            title="Belum Ada Lamaran"
                            description="Lamaran dari alumni akan muncul di sini"
                            icon="document"
                            class="py-8"
                        />
                        @endforelse
                    </div>
                    </div>
                </div>
            </div>
        </div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function initCharts() {
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
}
</script>
@endpush
</x-app-layout>
