<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="page-title text-xl sm:text-2xl">Pelamar</h2>
                <p class="page-subtitle text-sm">Kelola semua pelamar untuk lowongan perusahaan Anda.</p>
            </div>
            <form action="{{ route('company.applicants.index') }}" method="GET" class="flex gap-2 items-center">
                <select name="status" class="ui-select ui-btn-sm" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                    <option value="">Semua Status</option>
                    <option value="submitted"    {{ request('status') === 'submitted'    ? 'selected' : '' }}>Terkirim</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Ditinjau</option>
                    <option value="interviewed"  {{ request('status') === 'interviewed'  ? 'selected' : '' }}>Wawancara</option>
                    <option value="accepted"     {{ request('status') === 'accepted'     ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected"     {{ request('status') === 'rejected'     ? 'selected' : '' }}>Ditolak</option>
                </select>
                <x-ui.btn type="submit" size="sm">Saring</x-ui.btn>
            </form>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 space-y-4">
                    @forelse($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-900">
                                    {{ $application->job->title ?? 'Lowongan' }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-0.5">
                                    Pelamar: <span class="font-medium text-gray-800">{{ $application->user->name ?? '-' }}</span>
                                </p>
                                @if($application->cover_letter)
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($application->cover_letter, 150) }}
                                </p>
                                @endif
                                @if($application->attachment_path)
                                <p class="text-sm text-blue-600 mt-2">
                                    Lampiran: <a href="{{ asset('storage/' . $application->attachment_path) }}" target="_blank" class="font-semibold hover:underline">{{ $application->attachment_name ?? 'Buka berkas' }}</a>
                                </p>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                <span class="text-xs text-gray-400">{{ $application->created_at->format('d M Y') }}</span>
                                @php
                                    $statusMap = [
                                        'submitted'    => ['bg-blue-100 text-blue-700',   'Terkirim'],
                                        'under_review' => ['bg-yellow-100 text-yellow-700','Ditinjau'],
                                        'interviewed'  => ['bg-purple-100 text-purple-700','Wawancara'],
                                        'accepted'     => ['bg-green-100 text-green-700',  'Diterima'],
                                        'rejected'     => ['bg-red-100 text-red-700',      'Ditolak'],
                                    ];
                                    [$cls, $label] = $statusMap[$application->status] ?? ['bg-gray-100 text-gray-700', $application->status];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="mt-4 flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('applications.show', $application) }}"
                               class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                Lihat Detail
                            </a>

                            @if(in_array($application->status, ['submitted', 'under_review']))
                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="under_review">
                                <button type="submit"
                                        class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-semibold rounded-lg hover:bg-yellow-600 transition">
                                    Tinjau
                                </button>
                            </form>

                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="interviewed">
                                <button type="submit"
                                        class="px-3 py-1.5 bg-purple-600 text-white text-xs font-semibold rounded-lg hover:bg-purple-700 transition">
                                    Jadwalkan Interview
                                </button>
                            </form>
                            @endif

                            @if(in_array($application->status, ['submitted', 'under_review', 'interviewed']))
                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit"
                                        onclick="return confirm('Terima lamaran dari {{ addslashes($application->user->name ?? '') }}?')"
                                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                    Terima
                                </button>
                            </form>

                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        onclick="return confirm('Tolak lamaran ini?')"
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                                    Tolak
                                </button>
                            </form>
                            @endif

                            @if($application->status === 'accepted')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Diterima
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada pelamar</h3>
                        <p class="text-sm text-gray-500">
                            @if(request('status'))
                                Tidak ada pelamar dengan status "{{ $statusMap[request('status')][1] ?? request('status') }}".
                            @else
                                Pastikan lowongan sudah aktif agar pelamar bisa menemukannya.
                            @endif
                        </p>
                    </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($applications->hasPages())
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                    @endif
                </div>

                {{-- Sidebar Ringkasan --}}
                <aside class="space-y-4">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Ringkasan Status</h3>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total</span>
                                <span class="font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-t border-gray-50">
                                <span class="text-blue-600">Terkirim</span>
                                <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['submitted'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-600">Ditinjau</span>
                                <span class="font-semibold text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['under_review'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-600">Wawancara</span>
                                <span class="font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['interviewed'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-green-600">Diterima</span>
                                <span class="font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['accepted'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-red-500">Ditolak</span>
                                <span class="font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['rejected'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Link cepat --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Filter Cepat</h3>
                        <div class="space-y-1.5">
                            <a href="{{ route('company.applicants.index') }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-gray-50 transition {{ !request('status') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600' }}">
                                <span>Semua</span>
                                <span class="text-xs font-bold">{{ $stats['total'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'submitted']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-blue-50 transition {{ request('status') === 'submitted' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600' }}">
                                <span>Terkirim</span>
                                <span class="text-xs font-bold">{{ $stats['submitted'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'interviewed']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-purple-50 transition {{ request('status') === 'interviewed' ? 'bg-purple-50 text-purple-700 font-medium' : 'text-gray-600' }}">
                                <span>Wawancara</span>
                                <span class="text-xs font-bold">{{ $stats['interviewed'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'accepted']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition {{ request('status') === 'accepted' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-600' }}">
                                <span>Diterima</span>
                                <span class="text-xs font-bold">{{ $stats['accepted'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-app-layout>
