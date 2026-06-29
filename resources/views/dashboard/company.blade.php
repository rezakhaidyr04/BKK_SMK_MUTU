<x-app-layout :full-bleed="true">
    <div class="page-shell">
        {{-- Header --}}
        <x-ui.dashboard-hero
            title="Dasbor Rekrutmen"
            :subtitle="$company->name ?? 'Perusahaan Anda'"
            gradient="from-violet-600 via-purple-600 to-indigo-600"
        >
            <x-slot:actions>
                <a href="{{ route('company.jobs.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Posting Lowongan
                </a>
            </x-slot:actions>
        </x-ui.dashboard-hero>

        {{-- Verification Banner --}}
        @if($company->verification_status === 'rejected')
        <div style="background:#fef2f2; border-left:4px solid #dc2626;" class="px-6 py-5" role="alert">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#dc2626" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold" style="color:#991b1b">Verifikasi Ditolak</p>
                    @if($company->rejection_reason)
                    <p class="text-sm mt-1" style="color:#7f1d1d"><span class="font-semibold">Alasan:</span> {{ $company->rejection_reason }}</p>
                    @endif
                    <p class="text-sm mt-2" style="color:#991b1b">
                        Perbaiki profil perusahaan Anda, lalu hubungi admin.
                        <a href="{{ route('company.profile.edit') }}" class="font-semibold underline">Perbaiki profil →</a>
                    </p>
                </div>
            </div>
        </div>
        @elseif($company->verification_status === 'verified')
        <div style="background:#f0fdf4; border-left:4px solid #16a34a;" class="px-6 py-3" role="status">
            <div class="max-w-7xl mx-auto flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold" style="color:#14532d">Akun terverifikasi &mdash; Anda dapat memposting lowongan</p>
            </div>
        </div>
        @else
        <div style="background:#fefce8; border-left:4px solid #ca8a04;" class="px-6 py-5" role="alert">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#ca8a04" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold" style="color:#92400e">Menunggu Verifikasi Admin</p>
                    <p class="text-sm mt-0.5" style="color:#78350f">
                        Akun Anda sedang ditinjau oleh admin BKK SMK MUTU.
                        <a href="{{ route('company.profile.edit') }}" class="font-semibold underline" style="color:#92400e">Cek profil →</a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="page-container page-section">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.dashboard-stat-card
                    label="Lowongan Aktif"
                    :value="$stats['active_jobs'] ?? 0"
                    color="blue"
                    :href="route('company.jobs.index')"
                    hrefLabel="Kelola →"
                    class="animate-slide-up animate-slide-up-1"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Total Pelamar"
                    :value="$stats['total_applicants'] ?? 0"
                    color="green"
                    :href="route('company.applicants.index')"
                    hrefLabel="Lihat semua →"
                    class="animate-slide-up animate-slide-up-2"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Lamaran Baru"
                    :value="$stats['new_applicants'] ?? 0"
                    color="yellow"
                    footnote="Menunggu tinjauan"
                    class="animate-slide-up animate-slide-up-3"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>

                <x-ui.dashboard-stat-card
                    label="Interview"
                    :value="$stats['scheduled_interviews'] ?? 0"
                    color="purple"
                    footnote="Terjadwal"
                    class="animate-slide-up animate-slide-up-4"
                >
                    <x-slot:icon>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </x-slot:icon>
                </x-ui.dashboard-stat-card>
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Pelamar Terbaru --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pelamar Terbaru</h3>
                        <a href="{{ route('company.applicants.index') }}" class="text-sm text-blue-600 dark:text-blue-400 font-semibold hover:underline">Lihat semua</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentApplicants->take(8) as $app)
                        @php
                            $statusMap = [
                                'submitted'    => ['bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',   'Baru'],
                                'under_review' => ['bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400','Ditinjau'],
                                'interviewed'  => ['bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400','Interview'],
                                'accepted'     => ['bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',  'Diterima'],
                                'rejected'     => ['bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',      'Ditolak'],
                            ];
                            [$cls, $label] = $statusMap[$app->status] ?? ['bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300', $app->status];
                        @endphp
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                <span class="text-blue-700 dark:text-blue-400 font-bold text-lg">{{ substr($app->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $app->job->title ?? 'Lowongan' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                <a href="{{ route('applications.show', $app) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                <svg class="w-10 h-10 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Pelamar</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs mx-auto">Posting lowongan untuk mulai menerima lamaran dari kandidat berkualitas.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar Kanan --}}
                <div class="space-y-6">
                    {{-- Performa Lowongan --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Performa Lowongan</h3>
                        @forelse($jobPerformance as $job)
                        @php $maxApps = $jobPerformance->max('applications_count') ?: 1; @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300 font-medium truncate flex-1 mr-2">{{ $job->title }}</span>
                                <span class="text-gray-900 dark:text-white font-bold flex-shrink-0">{{ $job->applications_count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-purple-500 h-2.5 rounded-full animate-progress-fill" style="width: {{ ($job->applications_count / $maxApps) * 100 }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-3 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center animate-gentle-float" aria-hidden="true">
                                <svg class="w-8 h-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Belum ada data performa.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <x-ui.quick-action-link :href="route('company.jobs.create')" label="Posting Lowongan Baru" accentColor="purple">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </x-slot:icon>
                            </x-ui.quick-action-link>
                            <x-ui.quick-action-link :href="route('company.applicants.index')" label="Lihat Semua Pelamar" accentColor="purple">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </x-slot:icon>
                            </x-ui.quick-action-link>
                            <x-ui.quick-action-link :href="route('company.jobs.index')" label="Kelola Semua Lowongan" accentColor="purple">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </x-slot:icon>
                            </x-ui.quick-action-link>
                            <x-ui.quick-action-link :href="route('company.profile.edit')" label="Edit Profil Perusahaan" accentColor="purple">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </x-slot:icon>
                            </x-ui.quick-action-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
