<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Dasbor Rekrutmen</h1>
                        <p class="text-purple-100">{{ $company->name ?? 'Perusahaan Anda' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden md:block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white">
                            <p class="text-sm">Hari Ini</p>
                            <p class="font-semibold">{{ now()->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('company.jobs.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Posting Lowongan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner verifikasi --}}
        @if($company->verification_status === 'rejected')
        <div style="background:#fef2f2; border-left:4px solid #dc2626;" class="px-6 py-5">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#dc2626" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div style="background:#f0fdf4; border-left:4px solid #16a34a;" class="px-6 py-3">
            <div class="max-w-7xl mx-auto flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold" style="color:#14532d">Akun terverifikasi &mdash; Anda dapat memposting lowongan</p>
            </div>
        </div>
        @else
        <div style="background:#fefce8; border-left:4px solid #ca8a04;" class="px-6 py-5">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#ca8a04" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Lowongan Aktif</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['active_jobs'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('company.jobs.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">Kelola →</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Pelamar</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_applicants'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('company.applicants.index') }}" class="text-sm text-green-600 font-semibold hover:underline">Lihat semua →</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Lamaran Baru</p>
                            <p class="text-4xl font-bold text-yellow-600">{{ $stats['new_applicants'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">Menunggu tinjauan</div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Interview</p>
                            <p class="text-4xl font-bold text-purple-600">{{ $stats['scheduled_interviews'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-500">Terjadwal</div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Pelamar Terbaru -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Pelamar Terbaru</h3>
                        <a href="{{ route('company.applicants.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">Lihat semua</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentApplicants->take(8) as $app)
                        @php
                            $statusMap = [
                                'submitted'    => ['bg-blue-100 text-blue-700',   'Baru'],
                                'under_review' => ['bg-yellow-100 text-yellow-700','Ditinjau'],
                                'interviewed'  => ['bg-purple-100 text-purple-700','Interview'],
                                'accepted'     => ['bg-green-100 text-green-700',  'Diterima'],
                                'rejected'     => ['bg-red-100 text-red-700',      'Ditolak'],
                            ];
                            [$cls, $label] = $statusMap[$app->status] ?? ['bg-gray-100 text-gray-700', $app->status];
                        @endphp
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-bold text-lg">{{ substr($app->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ $app->job->title ?? 'Lowongan' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                <a href="{{ route('applications.show', $app) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada pelamar.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sidebar Kanan -->
                <div class="space-y-6">
                    <!-- Performa Lowongan -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Performa Lowongan</h3>
                        @forelse($jobPerformance as $job)
                        @php $maxApps = $jobPerformance->max('applications_count') ?: 1; @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 font-medium truncate flex-1 mr-2">{{ $job->title }}</span>
                                <span class="text-gray-900 font-bold flex-shrink-0">{{ $job->applications_count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-purple-500 h-2.5 rounded-full" style="width: {{ ($job->applications_count / $maxApps) * 100 }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <p class="text-gray-400 text-sm">Belum ada data performa.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Aksi Cepat -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('company.jobs.create') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Posting Lowongan Baru
                            </a>
                            <a href="{{ route('company.applicants.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Lihat Semua Pelamar
                            </a>
                            <a href="{{ route('company.jobs.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Kelola Semua Lowongan
                            </a>
                            <a href="{{ route('company.profile.edit') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Edit Profil Perusahaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
