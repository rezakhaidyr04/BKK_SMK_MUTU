<x-app-layout>
    <div class="page-shell">
        {{-- Analytics Header --}}
        <x-ui.dashboard-hero
            title="Analitik Rekrutmen"
            subtitle="Pantau performa lowongan dan metrik rekrutmen Anda"
            gradient="from-violet-600 via-purple-600 to-indigo-600"
        />

        <div class="page-container page-section">
            {{-- Key Metrics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Views</p>
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalViews }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total tampilan lowongan</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Conversion Rate</p>
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $conversionRate }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Views ke Applications</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Time to Hire</p>
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ round($timeToHire ?? 0) }} hari</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rata-rata waktu rekrutmen</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Acceptance Rate</p>
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $acceptanceRate }}%</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Interview ke Diterima</p>
                </div>
            </div>

            {{-- Conversion Funnel --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Funnel Konversi</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-300">Total Views</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $totalViews }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-blue-500 h-4 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-300">Applications ({{ $conversionRate }}%)</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $totalApplications }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ min($conversionRate, 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-300">Interviews ({{ $interviewRate }}%)</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $totalInterviews }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-purple-500 h-4 rounded-full" style="width: {{ min($interviewRate, 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 dark:text-gray-300">Accepted ({{ $acceptanceRate }}%)</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $totalAccepted }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-4">
                            <div class="bg-yellow-500 h-4 rounded-full" style="width: {{ min($acceptanceRate, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Job Performance --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Performa Lowongan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Lowongan</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Views</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Applications</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Interviews</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Accepted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $job->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $job->created_at->format('M Y') }}</p>
                                </td>
                                <td class="py-3 px-4 text-center text-gray-900 dark:text-white">{{ $job->views ?? 0 }}</td>
                                <td class="py-3 px-4 text-center text-gray-900 dark:text-white">{{ $job->applications_count }}</td>
                                <td class="py-3 px-4 text-center text-gray-900 dark:text-white">
                                    {{ Application::where('job_id', $job->id)->where('status', 'interviewed')->count() }}
                                </td>
                                <td class="py-3 px-4 text-center text-gray-900 dark:text-white">
                                    {{ Application::where('job_id', $job->id)->where('status', 'accepted')->count() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data lowongan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Status Distribution --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Distribusi Status Lamaran</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
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
                    @endphp
                    @foreach($statusLabels as $key => $label)
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <div class="w-12 h-12 {{ $statusColors[$key] }} rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold">
                            {{ $statusDistribution[$key] ?? 0 }}
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
