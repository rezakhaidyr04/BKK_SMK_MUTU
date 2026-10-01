<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Detail Pengguna" :subtitle="$user->name" eyebrow="Admin › Pengguna">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ ucfirst($user->role) }} · {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="page-banner__chip">
                    {{ $user->email_verified_at ? 'Email Terverifikasi' : 'Email Belum Verifikasi' }}
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.edit', $user) }}" variant="white" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </x-ui.btn>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <div class="max-w-4xl mx-auto space-y-4">

                {{-- Kepala profil --}}
                <div class="bg-white rounded-2xl border border-slate-100 border-t-4 border-t-blue-600 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-wrap gap-5">
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                     class="w-14 h-14 rounded-full object-cover shrink-0 ring-2 ring-blue-100">
                            @else
                                <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h2 class="text-lg font-bold text-slate-900 truncate">{{ $user->name }}</h2>
                                <p class="mt-0.5 flex items-center gap-1.5 text-sm text-slate-500 truncate">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="truncate">{{ $user->email }}</span>
                                </p>
                                <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                    <x-ui.status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-500 capitalize">{{ $user->role }}</span>
                                </div>
                            </div>
                        </div>
                        <dl class="w-full sm:w-auto sm:min-w-[190px] sm:border-l sm:border-slate-100 sm:pl-5 space-y-2.5 text-[13px]">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <div><dt class="text-slate-400">Terdaftar</dt><dd class="font-bold text-slate-900">{{ $user->created_at->format('d M Y') }}</dd></div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div><dt class="text-slate-400">Verifikasi email</dt><dd class="font-bold {{ $user->email_verified_at ? 'text-slate-900' : 'text-red-500' }}">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Belum' }}</dd></div>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($user->role === 'umum')
                    {{-- Kotak statistik --}}
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                        @php
                            $kotak = [
                                ['Lamaran', $user->applications->count(), 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                ['Wawancara', $applicationStats['interviewed'] ?? 0, 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                                ['Diterima', $applicationStats['accepted'] ?? 0, 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['Sertifikat', $user->certificates->count(), 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                                ['File CV', $user->cvFiles->count(), 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                ['Bookmark', $user->bookmarks->count(), 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
                            ];
                        @endphp
                        @foreach($kotak as [$label, $value, $icon])
                            <div class="rounded-xl border border-blue-100 bg-blue-50 px-2 py-4 text-center shadow-sm transition hover:bg-blue-100/60">
                                <svg class="w-5 h-5 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                <p class="mt-1.5 text-xl font-extrabold text-blue-800 leading-none tabular-nums">{{ $value }}</p>
                                <p class="mt-1 text-[11px] text-slate-500 truncate">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                @elseif($user->role === 'company')
                    <div class="grid grid-cols-3 gap-3">
                        @php
                            $kotak = [
                                ['Total Lowongan', $companyJobs->count(), 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['Lowongan Aktif', $companyJobs->where('status', 'active')->count(), 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['Total Pelamar', $companyJobs->sum('applications_count'), 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            ];
                        @endphp
                        @foreach($kotak as [$label, $value, $icon])
                            <div class="rounded-xl border border-blue-100 bg-blue-50 px-2 py-4 text-center shadow-sm transition hover:bg-blue-100/60">
                                <svg class="w-5 h-5 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                <p class="mt-1.5 text-xl font-extrabold text-blue-800 leading-none tabular-nums">{{ $value }}</p>
                                <p class="mt-1 text-[11px] text-slate-500 truncate">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Biodata --}}
                <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Biodata</span>
                            <span class="block text-xs text-slate-400 truncate">Data diri pengguna</span>
                        </span>
                        <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </summary>
                    <div class="px-5 pb-5">
                        @php
                            $ttl = trim(($user->birth_place ?? '') . ($user->birth_date ? ', ' . $user->birth_date->format('d M Y') : '')) ?: null;
                            $biodata = [
                                ['Telepon', $user->phone, 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                                ['Alamat', $user->address, 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                                ['Tempat, tanggal lahir', $ttl, 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                ['Jenis kelamin', $user->gender, 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['Posisi diincar', $user->preferred_position, 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['LinkedIn', $user->linkedin_url, 'M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244'],
                                ['Portofolio', $user->portfolio_url, 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                            ];
                        @endphp
                        <dl class="grid sm:grid-cols-2 gap-x-8">
                            @foreach($biodata as [$label, $value, $icon])
                                <div class="flex items-center gap-3 border-b border-slate-50 py-3">
                                    <svg class="w-[18px] h-[18px] shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                                    <dt class="w-32 shrink-0 text-[13px] text-slate-400">{{ $label }}</dt>
                                    <dd class="min-w-0 flex-1 truncate text-sm font-medium text-slate-800">
                                        @if($value && in_array($label, ['LinkedIn', 'Portofolio']))
                                            <a href="{{ $value }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">{{ Str::limit($value, 32) }}</a>
                                        @else
                                            {{ $value ?? '–' }}
                                        @endif
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                        @foreach(['Bio' => $user->bio, 'Riwayat pendidikan' => $user->education_history, 'Pengalaman organisasi' => $user->experience_organization] as $label => $value)
                            @if($value)
                                <div class="mt-4 text-sm">
                                    <p class="text-xs text-slate-400 mb-1">{{ $label }}</p>
                                    <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $value }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </details>

                @if($user->role === 'umum')
                    {{-- Keahlian --}}
                    <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                        <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Keahlian</span>
                                <span class="block text-xs text-slate-400 truncate">Kemampuan dan tingkat penguasaan</span>
                            </span>
                            <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </summary>
                        <div class="px-5 pb-5">
                            @if($user->skills->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->skills as $skill)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1.5 text-[13px] font-semibold text-blue-800">
                                            {{ $skill->name }}
                                            @if($skill->pivot->proficiency)
                                                <span class="text-blue-400">· level {{ $skill->pivot->proficiency }}/5</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 px-4 py-7 text-center">
                                    <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    </span>
                                    <p class="mt-2 text-sm font-bold text-slate-700">Belum ada keahlian tercatat.</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Data akan muncul jika pengguna menambahkan keahlian.</p>
                                </div>
                            @endif
                        </div>
                    </details>

                    {{-- Riwayat Lamaran --}}
                    <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                        <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Riwayat Lamaran</span>
                                <span class="block text-xs text-slate-400 truncate">Posisi yang pernah dilamar</span>
                            </span>
                            <span class="ml-auto rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">{{ $user->applications->count() }} lamaran</span>
                            <svg class="w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </summary>
                        <div class="px-5 pb-5">
                            @if($user->applications->count() > 0)
                                <ol class="divide-y divide-slate-100">
                                    @foreach($user->applications->sortByDesc('created_at') as $application)
                                        <li class="py-3 flex items-center justify-between gap-4">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ $application->job->title ?? 'Lowongan dihapus' }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $application->job->company_name ?? '' }} · {{ $application->created_at->format('d M Y') }}</p>
                                            </div>
                                            <x-ui.status-badge :status="$application->status" />
                                        </li>
                                    @endforeach
                                </ol>
                            @else
                                <div class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 px-4 py-7 text-center">
                                    <span class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </span>
                                    <p class="mt-2 text-sm font-bold text-slate-700">Belum pernah melamar lowongan.</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Riwayat akan muncul setelah pengguna melamar pekerjaan.</p>
                                </div>
                            @endif
                        </div>
                    </details>

                    @if($user->bookmarks->count() > 0)
                        <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                            <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Lowongan Disimpan</span>
                                    <span class="block text-xs text-slate-400 truncate">{{ $user->bookmarks->count() }} lowongan ditandai</span>
                                </span>
                                <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </summary>
                            <ul class="px-5 pb-5 divide-y divide-slate-100 text-sm">
                                @foreach($user->bookmarks->take(8) as $job)
                                    <li class="py-2.5 text-slate-800">{{ $job->title }} <span class="text-slate-400 text-xs">· {{ $job->company_name }}</span></li>
                                @endforeach
                            </ul>
                        </details>
                    @endif

                    @if($user->eventRegistrations->count() > 0)
                        <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                            <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Acara Diikuti</span>
                                    <span class="block text-xs text-slate-400 truncate">{{ $user->eventRegistrations->count() }} acara</span>
                                </span>
                                <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </summary>
                            <ul class="px-5 pb-5 divide-y divide-slate-100 text-sm">
                                @foreach($user->eventRegistrations as $registration)
                                    <li class="py-2.5 flex items-center justify-between gap-4">
                                        <span class="text-slate-800">{{ $registration->event->title ?? 'Acara dihapus' }}</span>
                                        <x-ui.status-badge :status="$registration->status ?? 'pending'" />
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                @endif

                @if($user->role === 'company' && $user->company)
                    @php $company = $user->company; @endphp
                    <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                        <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Perusahaan</span>
                                <span class="block text-xs text-slate-400 truncate">{{ $company->industry ?? 'Profil badan usaha' }}</span>
                            </span>
                            <span class="ml-auto"><x-ui.status-badge :status="$company->isApproved() ? 'verified' : 'pending'">{{ $company->isApproved() ? 'Terverifikasi' : 'Belum Verifikasi' }}</x-ui.status-badge></span>
                            <svg class="w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </summary>
                        <div class="px-5 pb-5">
                            <p class="text-base font-bold text-slate-900">{{ $company->name }}</p>
                            <dl class="mt-3 grid sm:grid-cols-2 gap-x-8 text-sm">
                                <div class="flex items-baseline justify-between gap-4 border-b border-slate-50 py-2.5">
                                    <dt class="shrink-0 text-slate-400">Email</dt>
                                    <dd class="font-medium text-slate-900 text-right break-all min-w-0">{{ $company->email ?? '–' }}</dd>
                                </div>
                                <div class="flex items-baseline justify-between gap-4 border-b border-slate-50 py-2.5">
                                    <dt class="shrink-0 text-slate-400">Telepon</dt>
                                    <dd class="font-medium text-slate-900 text-right min-w-0">{{ $company->phone ?? '–' }}</dd>
                                </div>
                            </dl>
                            @if($company->address)
                                <p class="mt-3 text-sm text-slate-600">{{ $company->address }}</p>
                            @endif
                            <a href="{{ route('admin.companies.show', $company) }}" class="mt-3 inline-block text-sm font-semibold text-blue-600 hover:underline">Buka profil perusahaan →</a>
                            @if($companyJobs->count() > 0)
                                <h4 class="mt-5 text-xs text-slate-400">Lowongan terbaru</h4>
                                <ul class="mt-1 divide-y divide-slate-100 text-sm">
                                    @foreach($companyJobs as $job)
                                        <li class="py-2.5 flex items-center justify-between gap-4">
                                            <span class="text-slate-800">{{ $job->title }} <span class="text-slate-400 text-xs">· {{ $job->applications_count }} pelamar</span></span>
                                            <x-ui.status-badge :status="$job->status" />
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </details>
                @endif

                {{-- Dokumen --}}
                <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Dokumen</span>
                            @php $hasDocs = $user->cvFiles->count() + $user->certificates->count() + $user->documents->count(); @endphp
                            <span class="block text-xs text-slate-400 truncate">{{ $hasDocs }} berkas terlampir</span>
                        </span>
                        <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </summary>
                    <div class="px-5 pb-5">
                        @if($hasDocs > 0)
                            <ul class="divide-y divide-slate-100 text-sm">
                                @foreach($user->cvFiles as $cv)
                                    <li class="py-3 flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block font-semibold text-slate-800 truncate">CV {{ $cv->is_ats_friendly ? '(ATS)' : '' }}</span>
                                            <span class="block text-xs text-slate-400 truncate">{{ basename($cv->file_path) }}</span>
                                        </span>
                                        <a href="{{ route('cv.download', $cv) }}" class="shrink-0 rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-blue-700">Unduh</a>
                                    </li>
                                @endforeach
                                @foreach($user->certificates as $certificate)
                                    <li class="py-3 flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block font-semibold text-slate-800 truncate">{{ $certificate->title }}</span>
                                            <span class="block text-xs text-slate-400 truncate">{{ $certificate->issuer }}</span>
                                        </span>
                                        <span class="shrink-0 flex gap-2">
                                            <a href="{{ route('certificates.download', $certificate) }}?preview=1" target="_blank" rel="noopener" class="rounded-lg border border-slate-200 px-3.5 py-1.5 text-xs font-bold text-slate-600 transition hover:border-blue-600 hover:text-blue-700">Lihat</a>
                                            <a href="{{ route('certificates.download', $certificate) }}" class="rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-blue-700">Unduh</a>
                                        </span>
                                    </li>
                                @endforeach
                                @foreach($user->documents as $document)
                                    <li class="py-3 flex items-center gap-3">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block font-semibold text-slate-800 truncate">{{ $document->original_name ?? basename($document->file_path) }}</span>
                                            <span class="block text-xs text-slate-400 truncate">{{ $document->document_type }}</span>
                                        </span>
                                        <a href="{{ route('documents.download', $document) }}" class="shrink-0 rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-blue-700">Unduh</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-slate-400">Tidak ada dokumen — pengguna belum mengunggah CV, sertifikat, atau dokumen.</p>
                        @endif
                    </div>
                </details>

                {{-- Info Akun --}}
                <details open class="group rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <summary class="flex items-center gap-3 px-5 py-4 cursor-pointer list-none transition rounded-t-2xl hover:bg-blue-50/70 [&::-webkit-details-marker]:hidden">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[13px] font-extrabold uppercase tracking-wide text-slate-900">Info Akun</span>
                            <span class="block text-xs text-slate-400 truncate">Status akun pengguna</span>
                        </span>
                        <svg class="ml-auto w-5 h-5 shrink-0 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                    </summary>
                    <dl class="px-5 pb-5 divide-y divide-slate-100 text-sm">
                        <div class="flex items-center justify-between gap-6 py-2.5">
                            <dt class="shrink-0 text-slate-400">Status</dt>
                            <dd><x-ui.status-badge :status="$user->is_active ? 'active' : 'inactive'" /></dd>
                        </div>
                        <div class="flex items-center justify-between gap-6 py-2.5">
                            <dt class="shrink-0 text-slate-400">Role</dt>
                            <dd class="font-semibold text-slate-900 capitalize">{{ $user->role }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-6 py-2.5">
                            <dt class="shrink-0 text-slate-400">Email verifikasi</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Belum' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-6 py-2.5">
                            <dt class="shrink-0 text-slate-400">Terdaftar</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                    </dl>
                </details>

            </div>
        </div>
    </div>
</x-app-layout>
