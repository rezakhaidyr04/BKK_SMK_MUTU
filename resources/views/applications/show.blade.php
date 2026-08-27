<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero 
            title="Detail Lamaran" 
            subtitle="{{ $application->job->title }} - {{ $application->job->company_name ?? 'Perusahaan' }}" 
            :back-url="auth()->user()->role === 'company' ? route('company.applicants.index') : route('applications.index')"
            back-label="Kembali ke Daftar Lamaran"
        />

        <div class="page-container page-section">
            @php
                $statusConfig = [
                    'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Terkirim'],
                    'under_review' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Ditinjau'],
                    'interviewed' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'label' => 'Wawancara'],
                    'accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Diterima'],
                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                ];
                $status = $statusConfig[$application->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Tidak Diketahui'];
            @endphp

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <x-ui.panel>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ $application->job->title }}</h2>
                                <p class="text-slate-600 mt-1">{{ $application->job->company_name ?? 'Perusahaan' }}</p>
                            </div>
                            <x-ui.status-badge :status="$application->status" />
                        </div>

                        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Pelamar</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $application->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Lokasi Lowongan</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->job->location ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Tanggal Melamar</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->created_at->format('d M Y, H:i') }} WIB</dd>
                            </div>
                        </dl>
                    </x-ui.panel>

                    <x-ui.panel title="Surat Lamaran">
                        <div class="prose max-w-none text-slate-700 whitespace-pre-line text-sm">{{ $application->cover_letter ?: 'Tidak ada surat lamaran.' }}</div>
                    </x-ui.panel>

                    @if($application->attachment_path)
                    <x-ui.panel title="Lampiran">
                        <x-ui.btn href="{{ route('applications.attachment.download', $application) }}" variant="primary" size="sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Unduh {{ $application->attachment_name ?? 'Lampiran' }}
                        </x-ui.btn>
                    </x-ui.panel>
                    @endif

                    {{-- Info Jadwal Wawancara --}}
                    @if($application->interview_date)
                    <div class="rounded-xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-violet-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Informasi Wawancara
                        </h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Tanggal</dt>
                                <dd class="text-violet-900 font-bold">
                                    {{ $application->interview_date->locale('id')->translatedFormat('l, d F Y') }}
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Jam</dt>
                                <dd class="text-violet-900 font-bold">
                                    {{ $application->interview_date->format('H:i') }} WIB
                                </dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Tipe</dt>
                                <dd class="text-violet-900">
                                    @if($application->interview_type === 'online')
                                        <span class="inline-flex items-center gap-1 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            Online
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Tatap Muka (Offline)
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($application->interview_type === 'online' && $application->interview_link)
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Link Meeting</dt>
                                <dd>
                                    <a href="{{ $application->interview_link }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-blue-600 hover:underline break-all font-medium">
                                        {{ $application->interview_link }}
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </dd>
                            </div>
                            @endif
                            @if($application->interview_type === 'offline' && $application->interview_location)
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Lokasi</dt>
                                <dd class="text-violet-900">{{ $application->interview_location }}</dd>
                            </div>
                            @endif
                            @if($application->interview_notes)
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Catatan</dt>
                                <dd class="text-violet-900 whitespace-pre-wrap">{{ $application->interview_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                        <div class="mt-4 p-3 bg-white rounded-lg border border-violet-200 text-xs text-violet-700">
                            Hadir tepat waktu, bawa dokumen pendukung (CV, KTP, Ijazah), dan berpakaian rapi.
                        </div>
                    </div>
                    @endif

                    {{-- Hasil Akhir Seleksi --}}
                    @if($application->status === 'accepted')
                    <div class="rounded-xl border border-green-200 bg-green-50 p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-2 bg-green-100 rounded-full">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-green-900 font-bold text-lg">Selamat! Anda dinyatakan LULUS ✓</h3>
                                <p class="text-green-800 text-sm mt-1">Anda berhasil lolos seleksi untuk posisi <strong>{{ $application->job->title }}</strong>. Perusahaan akan segera menghubungi Anda untuk langkah berikutnya.</p>
                            </div>
                        </div>
                    </div>
                    @elseif($application->status === 'rejected')
                    <div class="rounded-xl border border-red-200 bg-red-50 p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 p-2 bg-red-100 rounded-full">
                                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-red-900 font-bold text-lg">Maaf, Anda dinyatakan Tidak Lolos</h3>
                                <p class="text-red-800 text-sm mt-1">Jangan patah semangat. Terus kembangkan kemampuan Anda dan coba lamar lowongan lain yang tersedia.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <x-ui.panel title="Timeline Seleksi">
                        <div class="space-y-4">
                            @foreach($timeline as $item)
                                <div class="flex gap-3">
                                    <div class="mt-1 h-3 w-3 shrink-0 rounded-full {{ $item['completed'] ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $item['label'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $item['date'] ? $item['date']->format('d M Y') : 'Menunggu update' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.panel>

                    <div class="space-y-3">
                        <x-ui.btn href="{{ route('jobs.show', $application->job) }}" class="w-full justify-center">
                            Lihat Detail Lowongan
                        </x-ui.btn>

                        @if(auth()->user()->role === 'umum' || auth()->user()->role === 'admin')
                        <x-ui.btn href="{{ route('applications.surat-pengantar', $application) }}" variant="secondary" class="w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Cetak Surat Pengantar
                        </x-ui.btn>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
