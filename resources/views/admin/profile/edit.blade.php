@php
    $adminAvatarExists = $admin->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($admin->avatar);
    $adminAvatarUrl = $adminAvatarExists ? asset('storage/' . ltrim($admin->avatar, '/')) : null;
    $adminInitial = strtoupper(substr($admin->name, 0, 1));
    $adminVerified = !($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) || $admin->hasVerifiedEmail();
    // Partial upload foto profil memakai $user — samakan dengan akun admin.
    $user = $admin;
@endphp

<x-app-layout title="Pengaturan Admin — BKKMu" description="Kelola foto profil, data akun, dan keamanan akun admin BKKMu." :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="Pengaturan Admin"
            subtitle="Kelola foto profil, data akun, dan keamanan akun admin Anda."
            eyebrow="Administrasi › Pengaturan"
            :back-url="route('dashboard')"
            back-label="Kembali ke Dashboard">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Administrator
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $adminVerified ? 'Terverifikasi' : 'Belum Verifikasi' }}
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section" x-data="{ currentTab: 'profile' }">

            {{-- Ringkasan akun admin --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        @if($adminAvatarUrl)
                            <img src="{{ $adminAvatarUrl }}" alt="Foto profil admin"
                                 class="h-16 w-16 rounded-2xl bg-white object-cover ring-1 ring-slate-200"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="hidden h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-extrabold text-white">
                                {{ $adminInitial }}
                            </div>
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-extrabold text-white">
                                {{ $adminInitial }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="truncate text-xl font-bold tracking-tight text-slate-900">{{ $admin->name }}</h2>
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                    Administrator
                                </span>
                                @if($adminVerified)
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
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $admin->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-4">
                        @foreach([
                            ['label' => 'Pengguna', 'value' => $stats['users'] ?? 0, 'route' => 'admin.users.index'],
                            ['label' => 'Perusahaan', 'value' => $stats['companies'] ?? 0, 'route' => 'admin.companies.index'],
                            ['label' => 'Lowongan', 'value' => $stats['jobs'] ?? 0, 'route' => 'admin.jobs.index'],
                            ['label' => 'Laporan', 'value' => $stats['pendingJobReports'] ?? 0, 'route' => 'admin.job-reports.index'],
                        ] as $stat)
                            <a href="{{ route($stat['route']) }}" class="rounded-2xl border border-slate-100 bg-slate-50/70 px-3.5 py-2.5 text-center transition hover:border-blue-200 hover:bg-blue-50/70">
                                <span class="block text-lg font-extrabold leading-none text-slate-900">{{ $stat['value'] }}</span>
                                <span class="mt-1 block text-[11px] font-semibold text-slate-400">{{ $stat['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Navigasi tab --}}
            <div class="mt-5 flex gap-2 overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm no-scrollbar">
                <button type="button" @click="currentTab = 'profile'"
                        :class="currentTab === 'profile' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil &amp; Foto
                </button>
                <button type="button" @click="currentTab = 'password'"
                        :class="currentTab === 'password' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 01-2-2V6a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2zm6-10V7a4 4 0 10-8 0v4h8z"/></svg>
                    Keamanan Sandi
                </button>
                <button type="button" @click="currentTab = 'shortcuts'"
                        :class="currentTab === 'shortcuts' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                        class="flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold outline-none transition-all">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Akses Cepat
                </button>
            </div>

            {{-- Konten tab --}}
            <div class="mt-5 text-slate-700">
                {{-- Tab: Profil & Foto --}}
                <div x-show="currentTab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <header class="border-b border-slate-100 pb-5 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-blue-600">Identitas Admin</p>
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Data Akun</h2>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-slate-500">
                            Kelola nama, email, dan nomor HP akun admin Anda.
                        </p>
                    </header>

                    {{-- Kolom data akun --}}
                    <div>
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                @method('patch')

                                <div>
                                    <span class="mb-1 flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <x-input-label for="name" :value="__('Nama Lengkap')" class="font-semibold text-slate-700" />
                                    </span>
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                                  :value="old('name', $admin->name)" required autocomplete="name" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <span class="mb-1 flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a4 4 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-slate-700" />
                                    </span>
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                                  :value="old('email', $admin->email)" required autocomplete="username" />
                                    <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

                                    @if ($admin instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $admin->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="text-sm text-slate-800">
                                                {{ __('Alamat email Anda belum diverifikasi.') }}
                                                <button form="send-verification" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    {{ __('Kirim ulang email verifikasi.') }}
                                                </button>
                                            </p>
                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">Link verifikasi baru telah dikirim.</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <span class="mb-1 flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <x-input-label for="phone" value="Nomor Handphone" class="font-semibold text-slate-700" />
                                    </span>
                                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                                  :value="old('phone', $admin->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
                                    <p class="mt-1 text-xs text-slate-400">Nomor ini dipakai untuk kontak internal BKK, bukan ditampilkan ke perusahaan.</p>
                                    <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
                                </div>

                                <div class="flex items-center gap-4 border-t border-slate-100 pt-5">
                                    <x-ui.btn type="submit" class="min-h-[48px] justify-center">
                                        {{ __('Simpan Perubahan') }}
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </x-ui.btn>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }"
                                           x-show="show"
                                           x-transition
                                           x-init="setTimeout(() => show = false, 3000)"
                                           class="text-sm text-green-600 font-semibold flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Data akun berhasil diperbarui.
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                </div>

                {{-- Tab: Keamanan Sandi --}}
                <div x-show="currentTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @php($passwordStepLabel = 'Proteksi Akun')
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Tab: Akses Cepat --}}
                <div x-show="currentTab === 'shortcuts'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <header class="border-b border-slate-100 pb-5 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-purple-500 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-violet-600">Administrasi</p>
                                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Akses Cepat</h2>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-slate-500">Shortcut ke modul yang paling sering dipakai admin BKK.</p>
                    </header>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach([
                            ['route' => 'admin.users.index', 'label' => 'Kelola Pengguna', 'desc' => 'Data siswa, alumni, dan perusahaan'],
                            ['route' => 'admin.companies.index', 'label' => 'Data Perusahaan', 'desc' => 'Verifikasi & persetujuan perusahaan'],
                            ['route' => 'admin.jobs.index', 'label' => 'Lowongan Kerja', 'desc' => 'Moderasi &broadcast lowongan'],
                            ['route' => 'admin.job-reports.index', 'label' => 'Laporan Lowongan', 'desc' => 'Tindak lanjuti laporan pengguna'],
                            ['route' => 'admin.contacts.index', 'label' => 'Pesan Kontak', 'desc' => 'Inbox helpdesk & permintaan'],
                            ['route' => 'admin.activities.index', 'label' => 'Log Aktivitas', 'desc' => 'Jejak audit tindakan admin'],
                            ['route' => 'admin.reports.index', 'label' => 'Laporan Data', 'desc' => 'Rekap & ekspor data BKK'],
                            ['route' => 'admin.reviews.index', 'label' => 'Ulasan Pengguna', 'desc' => 'Moderasi ulasan'],
                            ['route' => 'admin.api-tokens.index', 'label' => 'Token API', 'desc' => 'Kelola akses API Sanctum'],
                        ] as $shortcut)
                            <a href="{{ route($shortcut['route']) }}"
                               class="group flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 transition hover:border-blue-200 hover:bg-blue-50/70">
                                <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-bold text-slate-900">{{ $shortcut['label'] }}</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">{{ $shortcut['desc'] }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
