<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Lamaran</h1>
                    <p class="text-gray-600 mt-1">{{ $application->job->title }} - {{ $application->job->company->name ?? __('bkk.fallback.company') }}</p>
                </div>
                <a href="{{ auth()->user()->role === 'company' ? route('company.applicants.index') : route('applications.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
            </div>

            @php
                $statusConfig = [
                    'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Terkirim'],
                    'under_review' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Ditinjau'],
                    'interviewed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Wawancara'],
                    'accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Diterima'],
                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                ];
                $status = $statusConfig[$application->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Tidak Diketahui'];
            @endphp

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-xl bg-white p-6 shadow-lg border border-gray-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $application->job->title }}</h2>
                                <p class="text-gray-600 mt-1">{{ $application->job->company->name ?? __('bkk.fallback.company') }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pelamar</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lokasi Lowongan</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->job->location ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Melamar</dt>
                                <dd class="mt-1 text-gray-900">{{ $application->created_at->format('d M Y, H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-xl bg-white p-6 shadow-lg border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Surat Lamaran</h2>
                        <div class="prose max-w-none text-gray-700 whitespace-pre-line">{{ $application->cover_letter ?: 'Tidak ada surat lamaran.' }}</div>
                    </div>

                    @if($application->attachment_path)
                    <div class="rounded-xl bg-white p-6 shadow-lg border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">Lampiran</h2>
                        <a href="{{ asset('storage/' . $application->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Buka {{ $application->attachment_name ?? 'Lampiran' }}
                        </a>
                    </div>
                    @endif

                    {{-- Info Jadwal Wawancara --}}
                    @if($application->interview_date)
                    <div class="rounded-xl border-2 border-purple-200 bg-purple-50 p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-purple-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            📅 Jadwal Wawancara
                        </h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Tanggal</dt>
                                <dd class="text-purple-900 font-bold">
                                    {{ $application->interview_date->locale('id')->translatedFormat('l, d F Y') }}
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Jam</dt>
                                <dd class="text-purple-900 font-bold">
                                    {{ $application->interview_date->format('H:i') }} WIB
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Tempat</dt>
                                <dd class="text-purple-900">{{ $application->interview_location }}</dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Tipe</dt>
                                <dd class="text-purple-900">
                                    {{ $application->interview_type === 'online' ? '💻 Online' : '🏢 Tatap Muka' }}
                                </dd>
                            </div>
                            @if($application->interview_type === 'online' && $application->interview_link)
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Link</dt>
                                <dd>
                                    <a href="{{ $application->interview_link }}" target="_blank"
                                       class="text-blue-600 hover:underline break-all">
                                        {{ $application->interview_link }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            @if($application->interview_notes)
                            <div class="flex items-start gap-3">
                                <dt class="text-purple-600 font-semibold w-28 flex-shrink-0">Catatan</dt>
                                <dd class="text-purple-900">{{ $application->interview_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                        <div class="mt-4 p-3 bg-white rounded-lg border border-purple-200 text-xs text-purple-700">
                            💡 Hadir tepat waktu, bawa dokumen pendukung (CV, KTP, Ijazah), dan berpakaian rapi.
                        </div>
                    </div>
                    @endif
                </div>

                <aside class="rounded-xl bg-white p-6 shadow-lg border border-gray-100 h-fit">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        @foreach($timeline as $item)
                            <div class="flex gap-3">
                                <div class="mt-1 h-3 w-3 rounded-full {{ $item['completed'] ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $item['label'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['date'] ? $item['date']->format('d M Y') : 'Menunggu update' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('jobs.show', $application->job) }}"
                       class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Lihat Lowongan
                    </a>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
