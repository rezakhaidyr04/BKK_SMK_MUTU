<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $job->title }}</h1>
                        <p class="text-purple-100">{{ optional($job->company)->name ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.jobs.edit', $job) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                            Edit
                        </a>
                        <a href="{{ route('admin.jobs.index') }}"
                           class="inline-flex items-center px-5 py-2.5 border border-white/30 text-white text-sm font-semibold rounded-xl hover:bg-white/10 transition">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Sidebar -->
                <div class="lg:col-span-1 space-y-4">
                    <div class="bg-white rounded-2xl shadow-lg p-5 space-y-3 text-sm">
                        <h3 class="font-bold text-gray-900 text-base mb-3">Detail Lowongan</h3>
                        @php
                            $statusColors = ['active'=>'bg-green-100 text-green-700','inactive'=>'bg-gray-100 text-gray-700','closed'=>'bg-red-100 text-red-700','draft'=>'bg-yellow-100 text-yellow-700'];
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$job->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ \App\Support\Label::jobStatus($job->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <span class="text-gray-500">Lokasi</span>
                            <span class="font-medium text-gray-900">{{ $job->location ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Tipe</span>
                            <span class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $job->job_type ?? '-')) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Deadline</span>
                            <span class="font-medium text-gray-900">{{ optional($job->deadline)->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Gaji</span>
                            <span class="font-medium text-gray-900 text-xs">
                                @if($job->salary_min && $job->salary_max)
                                    Rp {{ number_format($job->salary_min/1e6,1) }}M – {{ number_format($job->salary_max/1e6,1) }}M
                                @else -
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <span class="text-gray-500">Pelamar</span>
                            <span class="font-bold text-indigo-600">{{ $job->applications->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Diposting</span>
                            <span class="font-medium text-gray-900">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Konten utama -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Deskripsi Pekerjaan</h3>
                        </div>
                        <div class="p-6 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->description ?: '-' }}</div>
                    </div>

                    @if($job->qualifications)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Kualifikasi</h3>
                        </div>
                        <div class="p-6 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $job->qualifications }}</div>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900">Pelamar ({{ $job->applications->count() }})</h3>
                        </div>
                        @if($job->applications->count() > 0)
                        <div class="divide-y divide-gray-50">
                            @foreach($job->applications as $application)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm flex-shrink-0">
                                        {{ substr($application->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ optional($application->user)->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @php
                                    $sc = ['submitted'=>'bg-blue-100 text-blue-700','under_review'=>'bg-yellow-100 text-yellow-700','interviewed'=>'bg-purple-100 text-purple-700','accepted'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc[$application->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ \App\Support\Label::applicationStatus($application->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="p-10 text-center text-gray-400 text-sm">Belum ada pelamar.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
