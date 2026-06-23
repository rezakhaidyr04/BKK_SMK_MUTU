<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Dasbor Rekrutmen</h1>
                        <p class="text-blue-100 mt-1 text-sm">{{ $company->name ?? 'Perusahaan Anda' }}</p>
                    </div>
                    <a href="{{ route('company.jobs.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Posting Lowongan Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- Banner verifikasi: 3 status --}}
        @if($company->verification_status === 'rejected')
        {{-- DITOLAK --}}
        <div style="background:#fef2f2; border-left:4px solid #dc2626;" class="px-6 py-5">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#dc2626" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold" style="color:#991b1b">Verifikasi Ditolak</p>
                    @if($company->rejection_reason)
                    <p class="text-sm mt-1" style="color:#7f1d1d">
                        <span class="font-semibold">Alasan:</span> {{ $company->rejection_reason }}
                    </p>
                    @endif
                    <p class="text-sm mt-2" style="color:#991b1b">
                        Perbaiki profil perusahaan Anda sesuai alasan di atas, lalu hubungi admin untuk pengajuan ulang.
                        <a href="{{ route('company.profile.edit') }}" class="font-semibold underline">Perbaiki profil &rarr;</a>
                    </p>
                </div>
            </div>
        </div>

        @elseif($company->verification_status === 'verified')
        {{-- TERVERIFIKASI --}}
        <div style="background:#f0fdf4; border-left:4px solid #16a34a;" class="px-6 py-3">
            <div class="max-w-7xl mx-auto flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" style="color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold" style="color:#14532d">Akun terverifikasi &mdash; Anda dapat memposting lowongan</p>
            </div>
        </div>

        @else
        {{-- MENUNGGU (pending) --}}
        <div style="background:#fefce8; border-left:4px solid #ca8a04;" class="px-6 py-5">
            <div class="max-w-7xl mx-auto flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" style="color:#ca8a04" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold" style="color:#92400e">Menunggu Verifikasi Admin</p>
                    <p class="text-sm mt-0.5" style="color:#78350f">
                        Akun Anda sedang dalam proses peninjauan oleh admin BKK SMK MUTU.
                        Pastikan profil perusahaan sudah lengkap untuk mempercepat proses verifikasi.
                        <a href="{{ route('company.profile.edit') }}" class="font-semibold underline" style="color:#92400e">Cek profil &rarr;</a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lowongan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_jobs'] ?? 0 }}</p>
                    <a href="{{ route('company.jobs.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Kelola →</a>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Pelamar</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applicants'] ?? 0 }}</p>
                    <a href="{{ route('company.applicants.index') }}" class="text-xs text-blue-600 mt-2 inline-block hover:underline">Lihat semua →</a>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lamaran Baru</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['new_applicants'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-2">Menunggu tinjauan</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Interview</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['scheduled_interviews'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-2">Terjadwal</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Pelamar Terbaru --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900">Pelamar Terbaru</h2>
                        <a href="{{ route('company.applicants.index') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($recentApplicants->take(8) as $app)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-700 font-semibold">
                                {{ substr($app->user->name ?? '?', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $app->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $app->job->title ?? 'Lowongan' }}</p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
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
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                <a href="{{ route('applications.show', $app) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-10 text-center text-gray-400 text-sm">
                            Belum ada pelamar.
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar kanan --}}
                <div class="space-y-6">
                    {{-- Performa Lowongan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Performa Lowongan</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($jobPerformance as $job)
                            <div class="flex items-center justify-between px-6 py-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $job->title }}</p>
                                    <div class="mt-1 w-full bg-gray-100 rounded-full h-1.5">
                                        @php $maxApps = $jobPerformance->max('applications_count') ?: 1; @endphp
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($job->applications_count / $maxApps) * 100 }}%"></div>
                                    </div>
                                </div>
                                <span class="ml-4 text-sm font-bold text-gray-700 flex-shrink-0">{{ $job->applications_count }}</span>
                            </div>
                            @empty
                            <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data performa.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="font-semibold text-gray-900 mb-3">Aksi Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('company.jobs.create') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Posting Lowongan Baru
                            </a>
                            <a href="{{ route('company.applicants.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Lihat Semua Pelamar
                            </a>
                            <a href="{{ route('company.jobs.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Kelola Semua Lowongan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
