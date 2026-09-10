<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="{{ __('Pengaturan Akun') }}"
            subtitle="Kelola profil, keamanan, dan dokumen akun Anda."
            :back-url="route('dashboard')"
            back-label="Kembali ke Dasbor"
        />

        <div class="page-container page-section" x-data="{ currentTab: 'profile' }">
            @php
                $avatarUrl = $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null;
                $roleLabel = match ($user->role) {
                    'company' => 'Perusahaan',
                    'admin' => 'Administrator',
                    default => 'Pencari Kerja',
                };
                $isVerified = !($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) || $user->hasVerifiedEmail();
                $isUmum = Auth::user()->isUmum();
                $completionItems = $isUmum
                    ? [
                        'Foto profil' => !empty($user->avatar),
                        'Nomor HP' => !empty($user->phone),
                        'Bio singkat' => !empty($user->bio ?? null),
                        'Keahlian' => $user->skills->count() > 0,
                        'Posisi diinginkan' => !empty($user->preferred_position ?? null),
                        'Jenis kelamin' => !empty($user->gender ?? null),
                        'Tempat & tanggal lahir' => !empty($user->birth_place ?? null) && !empty($user->birth_date),
                        'Alamat tinggal' => !empty($user->address ?? null),
                        'Riwayat pendidikan' => !empty($user->education_history ?? null),
                        'Pengalaman / organisasi' => !empty($user->experience_organization ?? null),
                        'Berkas / CV' => ($user->documents->count() ?? 0) > 0 || ($user->cvFiles->count() ?? 0) > 0,
                    ]
                    : [
                        'Foto profil' => !empty($user->avatar),
                        'Nomor HP' => !empty($user->phone),
                    ];
                $doneCount = count(array_filter($completionItems));
                $totalCount = max(count($completionItems), 1);
                $completion = (int) round($doneCount / $totalCount * 100);
                $missingItems = array_keys(array_filter($completionItems, fn ($v) => !$v));
            @endphp

            {{-- Ringkasan akun --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                                 class="h-16 w-16 rounded-2xl bg-white object-cover ring-1 ring-slate-200">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-extrabold text-white">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="truncate text-xl font-bold tracking-tight text-slate-900">{{ $user->name }}</h2>
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                    {{ $roleLabel }}
                                </span>
                                @if($isVerified)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-0.5 text-[11px] font-bold text-green-700">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-bold text-amber-700">
                                        Belum verifikasi
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('applications.index') }}" class="group flex items-center gap-2.5 rounded-2xl border border-slate-100 bg-slate-50/70 px-3.5 py-2.5 transition hover:border-blue-200 hover:bg-blue-50/70">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <span>
                                <span class="block text-lg font-extrabold leading-none text-slate-900">{{ $user->applications()->count() }}</span>
                                <span class="mt-1 block text-[11px] font-semibold text-slate-400">Lamaran</span>
                            </span>
                            <svg class="ml-1 h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('bookmarks.index') }}" class="group flex items-center gap-2.5 rounded-2xl border border-slate-100 bg-slate-50/70 px-3.5 py-2.5 transition hover:border-violet-200 hover:bg-violet-50/70">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            </span>
                            <span>
                                <span class="block text-lg font-extrabold leading-none text-slate-900">{{ $user->bookmarks()->count() }}</span>
                                <span class="mt-1 block text-[11px] font-semibold text-slate-400">Disimpan</span>
                            </span>
                            <svg class="ml-1 h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('certificates.index') }}" class="group hidden items-center gap-2.5 rounded-2xl border border-slate-100 bg-slate-50/70 px-3.5 py-2.5 transition hover:border-emerald-200 hover:bg-emerald-50/70 sm:flex">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <span>
                                <span class="block text-lg font-extrabold leading-none text-slate-900">{{ $user->certificates()->count() }}</span>
                                <span class="mt-1 block text-[11px] font-semibold text-slate-400">Sertifikat</span>
                            </span>
                            <svg class="ml-1 h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between text-xs">
                        <p class="font-semibold text-slate-600">Kelengkapan profil</p>
                        <p class="font-extrabold text-slate-900">{{ $completion }}%</p>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-600" style="width: {{ $completion }}%"></div>
                    </div>
                    @if(count($missingItems) > 0)
                        <p class="mt-2 text-xs text-slate-400">Belum lengkap: {{ implode(', ', array_slice($missingItems, 0, 3)) }}{{ count($missingItems) > 3 ? '…' : '' }}</p>
                    @else
                        <p class="mt-2 text-xs font-semibold text-green-600">Profil sudah lengkap.</p>
                    @endif
                </div>
            </div>

            {{-- Navigasi tab --}}
            <div class="mt-5 flex gap-2 overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
                <button type="button" @click="currentTab = 'profile'"
                        :class="currentTab === 'profile' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Diri
                </button>
                <button type="button" @click="currentTab = 'password'"
                        :class="currentTab === 'password' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Keamanan Sandi
                </button>
                @if($isUmum)
                <button type="button" @click="currentTab = 'documents'"
                        :class="currentTab === 'documents' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Berkas Pendukung
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 text-[11px] font-extrabold"
                          :class="currentTab === 'documents' ? 'bg-white text-slate-900' : 'bg-slate-100 text-slate-500'">{{ $user->documents->count() }}</span>
                </button>
                @endif
                @if($user->role !== 'admin')
                <button type="button" @click="currentTab = 'danger'"
                        :class="currentTab === 'danger' ? 'bg-red-600 text-white shadow-sm' : 'text-slate-500 hover:bg-red-50 hover:text-red-600'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    Zona Bahaya
                </button>
                @endif
            </div>

            {{-- Konten tab --}}
            <div class="mt-5 text-slate-700">
                <div x-show="currentTab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div x-show="currentTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                @if($isUmum)
                <div x-show="currentTab === 'documents'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.manage-documents-form')
                </div>
                @endif

                @if($user->role !== 'admin')
                <div x-show="currentTab === 'danger'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-view-box,
        .cropper-face {
          border-radius: 50%;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    @endpush
</x-app-layout>
