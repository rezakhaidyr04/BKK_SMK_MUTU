<x-app-layout>
    <div class="page-shell">
        {{-- Back Button --}}
        <div class="page-container pt-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="page-container page-section">
            {{-- Student Profile Header --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-green-600 h-32"></div>
                <div class="px-6 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16">
                        <div class="flex items-end gap-4">
                            @if($student->user->avatar ?? null)
                            <img src="{{ asset('storage/' . $student->user->avatar) }}" alt="{{ $student->user->name }}" class="w-24 h-24 rounded-2xl border-4 border-white dark:border-gray-800 shadow-lg object-cover">
                            @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-teal-500 to-green-500 border-4 border-white dark:border-gray-800 shadow-lg flex items-center justify-center text-white text-3xl font-bold">
                                {{ substr($student->user->name ?? '?', 0, 1) }}
                            </div>
                            @endif
                            <div class="pb-2">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $student->user->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400">{{ $student->major ?? '-' }} · {{ $student->graduation_year ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4 md:mt-0">
                            <a href="mailto:{{ $student->user->email }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Kirim Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Overview --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Lamaran</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_applications'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Ditinjau</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['under_review'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Interview</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['interviewed'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Diterima</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['accepted'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Application History --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Lamaran</h3>
                </div>
                <div class="p-6">
                    @forelse($applications as $application)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $application->job->title }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->job->company->name }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @php
                                $statusMap = [
                                    'submitted' => ['bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400', 'Terkirim'],
                                    'under_review' => ['bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400', 'Ditinjau'],
                                    'interviewed' => ['bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400', 'Interview'],
                                    'accepted' => ['bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400', 'Diterima'],
                                    'rejected' => ['bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400', 'Ditolak'],
                                ];
                                [$cls, $label] = $statusMap[$application->status] ?? ['bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300', $application->status];
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                            <p class="text-xs text-gray-400 mt-1">{{ $application->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Lamaran</h4>
                        <p class="text-gray-500 dark:text-gray-400">Siswa ini belum melamar lowongan apapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
