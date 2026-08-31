<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero
            title="Profile Pelamar"
            subtitle="{{ $application->user->name }} — {{ $application->job->title }}"
            :back-url="route('company.applicants.index')"
            back-label="Kembali ke Daftar Pelamar"
        />

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
                                <a href="{{ $application->user->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $application->user->linkedin_url }}</a>
                            </div>
                            @endif

                            @if($application->user->portfolio_url)
                            <div class="flex items-start gap-3 text-sm">
                                <span class="text-slate-400">💼</span>
                                <a href="{{ $application->user->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $application->user->portfolio_url }}</a>
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
                                <div class="flex items-center gap-2">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                                    </div>
                                    <span class="text-sm text-slate-700">{{ optional($cv->file_path)->basename($cv->file_path) ?? 'CV' }}</span>
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
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.612-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.612 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </span>
                                    <span class="text-slate-700">{{ $cert->title ?? 'Sertifikat' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.panel>
                    @endif
                </div>

                {{-- MAIN CONTENT --}}
                <div class="lg:col-span-2 space-y-6">
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
                        <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line">
                            {{ $application->user->bio }}
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- EDUCATION & EXPERIENCE --}}
                    @if($application->user->education_history || $application->user->experience_organization)
                    <x-ui.panel title="Pendidikan & Pengalaman">
                        <div class="grid gap-6 sm:grid-cols-2">
                            @if($application->user->education_history)
                            <div>
                                <h3 class="mb-2 text-sm font-bold text-slate-900">Pendidikan</h3>
                                <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line">
                                    {{ $application->user->education_history }}
                                </div>
                            </div>
                            @endif

                            @if($application->user->experience_organization)
                            <div>
                                <h3 class="mb-2 text-sm font-bold text-slate-900">Pengalaman Organisasi</h3>
                                <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line">
                                    {{ $application->user->experience_organization }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- SURAT LAMARAN --}}
                    @if($application->cover_letter)
                    <x-ui.panel title="Surat Lamaran">
                        <div class="prose max-w-none text-sm text-slate-700 whitespace-pre-line">
                            {{ $application->cover_letter }}
                        </div>
                    </x-ui.panel>
                    @endif

                    {{-- LAMPIRAN --}}
                    @if($application->attachment_path)
                    <x-ui.panel title="Lampiran Lamaran">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">{{ $application->attachment_name }}</p>
                                <p class="text-xs text-slate-400">{{ $application->attachment_size ? number_format($application->attachment_size / 1024, 1) : '-' }} KB</p>
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
</x-app-layout>