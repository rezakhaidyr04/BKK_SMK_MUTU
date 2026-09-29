<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="Profile Pelamar"
            subtitle="{{ $application->user->name }} — {{ $application->job->title }}"
            :back-url="route('company.applicants.index')"
            back-label="Kembali ke Daftar Pelamar" eyebrow="Perusahaan › Pelamar">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $application->user->name }}
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $application->job->title }}
                </span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- SIDEBAR: PROFILE --}}
                <div class="space-y-6">
                    <x-ui.panel>
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xl font-bold text-blue-600">
                                {{ strtoupper(substr($application->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $application->user->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $application->user->email }}</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3">
                            @if($application->user->phone)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">📞</span>
                                <span class="text-slate-700">{{ $application->user->phone }}</span>
                            </div>
                            @endif

                            @if($application->user->address)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">📍</span>
                                <span class="text-slate-700">{{ $application->user->address }}</span>
                            </div>
                            @endif

                            @if($application->user->birth_date)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">📅</span>
                                <span class="text-slate-700">{{ $application->user->birth_date->translatedFormat('d F Y') }} @ {{ $application->user->birth_place }}</span>
                            </div>
                            @endif

                            @if($application->user->gender)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">👤</span>
                                <span class="text-slate-700">{{ ucfirst($application->user->gender) }}</span>
                            </div>
                            @endif

                            @if($application->user->linkedin_url)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">🔗</span>
                                <div class="flex flex-col">
                                    <a href="{{ $application->user->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">
                                        LinkedIn - {{ $application->user->name }}
                                    </a>
                                    <span class="text-xs text-slate-400 mt-0.5">{{ $application->user->linkedin_url }}</span>
                                </div>
                            </div>
                            @endif

                            @if($application->user->portfolio_url)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">💼</span>
                                <div class="flex flex-col">
                                    <a href="{{ $application->user->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                        {{ $application->user->portfolio_type === 'drive' ? 'Google Drive' : 'Portfolio Website' }}
                                    </a>
                                    <span class="text-xs text-slate-400 mt-0.5">{{ $application->user->portfolio_url }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </x-ui.panel>

                    {{-- SKILLS --}}
                    @if($application->user->skills->isNotEmpty())
                    <x-ui.panel title="Keterampilan">
                        <div class="flex flex-wrap gap-2">
                            @foreach($application->user->skills as $skill)
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 border border-blue-100">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- CV FILES --}}
                    @if($application->user->cvFiles->isNotEmpty())
                    <x-ui.panel title="CV / Berkas">
                        <div class="space-y-3">
                            @foreach($application->user->cvFiles as $cv)
                                <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/50 p-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                                    </div>
                                    <span class="min-w-0 flex-1 truncate text-sm text-slate-700" title="{{ basename($cv->file_path) }}">{{ basename($cv->file_path) }}</span>
                                    <div class="flex shrink-0 items-center gap-1.5">
                                        <a href="{{ route('cv.download', $cv) }}?preview=1" target="_blank" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-blue-300 hover:text-blue-700">Lihat</a>
                                        <a href="{{ route('cv.download', $cv) }}" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-blue-300 hover:text-blue-700">Unduh</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- CERTIFICATES --}}
                    @if($application->user->certificates->isNotEmpty())
                    <x-ui.panel title="Sertifikat">
                        <div class="space-y-3">
                            @foreach($application->user->certificates as $cert)
                                <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/50 p-2.5 text-sm">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.612-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.612 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </span>
                                    <span class="min-w-0 flex-1 truncate text-slate-700" title="{{ $cert->title ?? 'Sertifikat' }}">{{ $cert->title ?? 'Sertifikat' }}</span>
                                    <div class="flex shrink-0 items-center gap-1.5">
                                        <a href="{{ route('certificates.download', $cert) }}?preview=1" target="_blank" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-green-300 hover:text-green-700">Lihat</a>
                                        <a href="{{ route('certificates.download', $cert) }}" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-green-300 hover:text-green-700">Unduh</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- DOKUMEN PENDUKUNG --}}
                    @if($application->user->documents->isNotEmpty())
                    <x-ui.panel title="Dokumen Pendukung">
                        <div class="space-y-3">
                            @foreach($application->user->documents as $doc)
                                <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/50 p-2.5 text-sm">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                                    </span>
                                    <span class="min-w-0 flex-1 truncate text-slate-700" title="{{ $doc->original_name ?? $doc->document_type }}">{{ $doc->original_name ?? $doc->document_type }}</span>
                                    <div class="flex shrink-0 items-center gap-1.5">
                                        <a href="{{ route('documents.download', $doc) }}?preview=1" target="_blank" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-blue-300 hover:text-blue-700">Lihat</a>
                                        <a href="{{ route('documents.download', $doc) }}" class="rounded-md border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-600 hover:border-blue-300 hover:text-blue-700">Unduh</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.panel>
                    @endif
                </div>

                {{-- MAIN CONTENT --}}
                <div class="lg:col-span-2 space-y-6 min-w-0">
                    {{-- LAMARAN INFO --}}
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
                                <dt class="text-sm font-medium text-slate-500">Tanggal Melamar</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->created_at->format('d M Y, H:i') }} WIB</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Lokasi Lowongan</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->job->location ?? '-' }}</dd>
                            </div>
                        </dl>
                    </x-ui.panel>

                    {{-- BIO --}}
                    @if($application->user->bio)
                    <x-ui.panel title="Profil Singkat">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4 text-sm text-slate-700 whitespace-pre-line break-words [overflow-wrap:anywhere] min-w-0 overflow-hidden leading-relaxed" style="text-align: left;">{{ str_replace(['\\r\\n', '\\n', '\\r'], "\n", trim($application->user->bio)) }}</div>
                    </x-ui.panel>
                    @endif

                    {{-- EDUCATION & EXPERIENCE --}}
                    @if($application->user->education_history || $application->user->experience_organization)
                    <x-ui.panel title="Pendidikan & Pengalaman">
                        <div class="grid gap-6 sm:grid-cols-2">
                            @if($application->user->education_history)
                            <div class="min-w-0">
                                <h3 class="mb-3 text-sm font-bold text-slate-900">Pendidikan</h3>
                                @php
                                    $eduLines = collect(preg_split('/\r\n|\r|\n|\\\\r\\\\n|\\\\n|\\\\r/', $application->user->education_history ?? ''))
                                        ->map(fn ($l) => trim($l))
                                        ->filter()
                                        ->values();
                                @endphp
                                @if($eduLines->isNotEmpty())
                                <ul class="space-y-2.5">
                                    @foreach($eduLines as $line)
                                    <li class="flex items-start gap-2.5 text-sm leading-relaxed text-slate-700 min-w-0">
                                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>
                                        <span class="break-words [overflow-wrap:anywhere] min-w-0">{{ $line }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-sm text-slate-400">-</p>
                                @endif
                            </div>
                            @endif

                            @if($application->user->experience_organization)
                            <div class="min-w-0">
                                <h3 class="mb-3 text-sm font-bold text-slate-900">Pengalaman Organisasi</h3>
                                @php
                                    $expLines = collect(preg_split('/\r\n|\r|\n|\\\\r\\\\n|\\\\n|\\\\r/', $application->user->experience_organization ?? ''))
                                        ->map(fn ($l) => trim($l))
                                        ->filter()
                                        ->values();
                                @endphp
                                @if($expLines->isNotEmpty())
                                <ul class="space-y-2.5">
                                    @foreach($expLines as $line)
                                    <li class="flex items-start gap-2.5 text-sm leading-relaxed text-slate-700 min-w-0">
                                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                                        <span class="break-words [overflow-wrap:anywhere] min-w-0">{{ $line }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-sm text-slate-400">-</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- SURAT LAMARAN --}}
                    @if($application->cover_letter)
                    <x-ui.panel title="Surat Lamaran">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4 text-sm leading-relaxed text-slate-700 whitespace-pre-line break-words [overflow-wrap:anywhere] min-w-0 overflow-hidden" style="text-align: left;">{{ str_replace(['\\r\\n', '\\n', '\\r'], "\n", trim($application->cover_letter)) }}</div>
                    </x-ui.panel>
                    @endif

                    {{-- LAMPIRAN --}}
                    @if($application->attachment_path)
                    <x-ui.panel title="Lampiran Lamaran">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-700" title="{{ $application->attachment_name }}">{{ $application->attachment_name }}</p>
                                <p class="text-xs text-slate-400">{{ $application->attachment_size ? number_format($application->attachment_size / 1024, 1) : '-' }} KB · {{ $application->attachment_mime ?? 'file' }}</p>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <a href="{{ route('applications.attachment.download', $application) }}?preview=1" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">Lihat</a>
                                <a href="{{ route('applications.attachment.download', $application) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">Unduh</a>
                            </div>
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- INFO WAWANCARA --}}
                    @if($application->interview_date)
                    <div class="rounded-xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-violet-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Informasi Wawancara
                        </h2>
                        <dl class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Tanggal</dt>
                                <dd class="text-violet-900 font-bold">{{ $application->interview_date->locale('id')->translatedFormat('l, d F Y') }}</dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Jam</dt>
                                <dd class="text-violet-900 font-bold">{{ $application->interview_date->format('H:i') }} WIB</dd>
                            </div>
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Tipe</dt>
                                <dd class="text-violet-900">
                                    @if($application->interview_type === 'online')
                                        <span class="inline-flex items-center gap-1 font-semibold">Online (Zoom/Meet)</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 font-semibold">Tatap Muka (Offline)</span>
                                    @endif
                                </dd>
                            </div>
                            @if($application->interview_type === 'online' && $application->interview_link)
                            <div class="flex items-start gap-3">
                                <dt class="text-violet-600 font-semibold w-28 flex-shrink-0">Link</dt>
                                <dd><a href="{{ $application->interview_link }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:underline break-all font-medium">{{ $application->interview_link }}</a></dd>
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
                    </div>
                    @endif

                    {{-- KELOLA STATUS — dengan konfirmasi Diterima/Ditolak --}}
                    <x-ui.panel title="Kelola Status Lamaran" subtitle="Ubah status seleksi pelamar ini.">
                        <form method="POST" action="{{ route('company.applications.update', $application) }}" class="js-status-form space-y-4" data-applicant-name="{{ $application->user->name }}" x-data="{ status: @js($application->status), type: @js($application->interview_type ?? 'offline') }">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Status Lamaran</label>
                                <select x-model="status" name="status" class="w-full rounded-xl border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="submitted" {{ $application->status === 'submitted' ? 'selected' : '' }}>Diajukan</option>
                                    <option value="under_review" {{ $application->status === 'under_review' ? 'selected' : '' }}>Sedang Ditinjau</option>
                                    <option value="interviewed" {{ $application->status === 'interviewed' ? 'selected' : '' }}>Wawancara Terjadwal</option>
                                    <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Lolos / Diterima</option>
                                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Tidak Lolos / Ditolak</option>
                                </select>
                            </div>
                            <div x-show="status === 'interviewed'" x-cloak class="space-y-4 rounded-xl border border-violet-100 bg-violet-50 p-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">Tanggal</label>
                                        <input type="date" name="interview_date" value="{{ $application->interview_date ? $application->interview_date->format('Y-m-d') : '' }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                    </div>
                                    <div>
                                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">Jam</label>
                                        <input type="time" name="interview_time" value="{{ $application->interview_date ? $application->interview_date->format('H:i') : '' }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">Tipe Wawancara</label>
                                    <select x-model="type" name="interview_type" class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="offline">Tatap Muka (Offline)</option>
                                        <option value="online">Online (Zoom/Meet)</option>
                                    </select>
                                </div>
                                <div x-show="type === 'online'" x-cloak>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">Link Wawancara</label>
                                    <input type="url" name="interview_link" value="{{ $application->interview_link }}" placeholder="https://zoom.us/..." class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div x-show="type === 'offline'">
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">Lokasi Wawancara</label>
                                    <input type="text" name="interview_location" value="{{ $application->interview_location }}" placeholder="Contoh: Ruang HRD" class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">Catatan Tambahan</label>
                                    <textarea name="interview_notes" rows="3" placeholder="Contoh: Harap membawa dokumen pendukung." class="w-full rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500">{{ $application->interview_notes }}</textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Simpan Perubahan</button>
                            </div>
                        </form>
                    </x-ui.panel>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-3">
                        <form action="{{ route('messages.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $application->user_id }}">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:bg-green-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5z"/></svg>
                                Chat Pelamar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Konfirmasi sebelum ubah status Diterima / Ditolak (temuan testing #6).
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !form.classList.contains('js-status-form')) return;
        if (form.dataset.confirmFired === '1') {
            form.dataset.confirmFired = '0';
            return;
        }
        const select = form.querySelector('select[name="status"]');
        const status = select ? select.value : '';
        if (status !== 'accepted' && status !== 'rejected') return;
        e.preventDefault();
        const name = form.getAttribute('data-applicant-name') || 'pelamar ini';
        if (typeof showConfirm !== 'function') {
            if (confirm(status === 'accepted' ? ('Apakah Anda yakin ingin menerima ' + name + '?') : ('Apakah Anda yakin ingin menolak lamaran ' + name + '?'))) {
                form.dataset.confirmFired = '1';
                form.submit();
            }
            return;
        }
        if (status === 'accepted') {
            confirmForm = form;
            showConfirm('Apakah Anda yakin ingin menerima pelamar ini? Status akan diubah menjadi Diterima dan pelamar akan menerima notifikasi.', {
                title: 'Terima Pelamar Ini?',
                eyebrow: 'KONFIRMASI PENERIMAAN',
                okText: 'Ya, Terima',
                infoMain: name,
                infoSub: 'Status lamaran akan diubah menjadi Diterima',
                variant: 'success',
            });
        } else {
            confirmForm = form;
            showConfirm('Apakah Anda yakin ingin menolak lamaran ini? Status akan diubah menjadi Ditolak dan pelamar akan menerima notifikasi.', {
                title: 'Tolak Lamaran Ini?',
                eyebrow: 'KONFIRMASI PENOLAKAN',
                okText: 'Ya, Tolak',
                infoMain: name,
                infoSub: 'Status lamaran akan diubah menjadi Ditolak',
                variant: 'danger',
            });
        }
    }, true);
    </script>
    @endpush
</x-app-layout>