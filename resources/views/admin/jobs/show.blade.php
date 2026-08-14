<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="{{ $job->title }}" subtitle="{{ $job->company_name ?? '-' }}">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.jobs.edit', $job) }}" variant="white" size="sm">Edit</x-ui.btn>
                <x-ui.btn href="{{ route('admin.jobs.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

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

                    <form action="{{ route('admin.jobs.broadcast', $job) }}" method="POST" class="mt-4" onsubmit="return confirm('Yakin ingin membroadcast notifikasi lowongan ini ke semua siswa melalui email?');">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Broadcast ke Siswa
                        </button>
                    </form>
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
                            <div class="px-6 py-4 flex flex-col hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                                <div class="flex items-center justify-between">
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
                                
                                @if(optional($application->user)->documents && $application->user->documents->count() > 0)
                                    <div class="mt-4 pt-3 border-t border-gray-100">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">Kelengkapan Berkas:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($application->user->documents as $doc)
                                                <a href="{{ route('documents.download', $doc) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded bg-gray-100 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 text-xs transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    {{ $doc->document_type }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="p-10 text-center text-gray-400 text-sm">Belum ada pelamar.</div>
                        @endif
                    </div>
                </div>
            </div>
</x-app-layout>
