<section>
    @push('styles')
    <style>
        .profile-form .ui-input,
        .profile-form select,
        .profile-form textarea {
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }
        .profile-form .ui-input:hover,
        .profile-form select:hover,
        .profile-form textarea:hover {
            border-color: #cbd5e1;
        }
        .profile-form div:has(> input:focus) > label,
        .profile-form div:has(> select:focus) > label,
        .profile-form div:has(> textarea:focus) > label {
            color: #1d4ed8;
        }
    </style>
    @endpush
    <header class="border-b border-slate-100 pb-5 mb-6">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-blue-600">Langkah 1 · Identitas</p>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">Informasi Profil</h2>
            </div>
        </div>
        <p class="mt-3 text-sm text-slate-500">
            @if(Auth::user()->role === 'umum')
                Perbarui foto, nama, email, nomor HP, bio, keahlian, dan profil karier Anda.
            @elseif(Auth::user()->role === 'company')
                Perbarui foto, nama, email, nomor HP, dan informasi perusahaan Anda.
            @else
                Perbarui foto, nama, email, nomor HP, dan informasi akun Anda.
            @endif
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form space-y-6">
        @csrf
        @method('patch')

        {{-- Foto + Kontak --}}
        <div class="grid gap-5 lg:grid-cols-[230px_minmax(0,1fr)]">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-center">
                @php
                    $avatarPreviewUrl = $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null;
                @endphp
                @if($avatarPreviewUrl)
                    <img src="{{ $avatarPreviewUrl }}" alt="" class="mx-auto h-24 w-24 rounded-2xl object-cover ring-1 ring-slate-200">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-3xl font-extrabold text-white">{{ substr($user->name, 0, 1) }}</div>
                @endif
                <label for="avatar" class="mt-3 inline-flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-700">
                    Pilih Foto Baru
                </label>
                <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp,image/gif"
                       onchange="previewAvatar(event)">
                <p class="mt-2 text-[11px] leading-relaxed text-slate-400">JPG, PNG, WebP, GIF · Maksimal 3MB.</p>

                @if($avatarPreviewUrl)
                    <img id="avatar-preview" src="{{ $avatarPreviewUrl }}" alt="" class="hidden">
                @else
                    <div id="avatar-preview-placeholder" class="hidden">{{ substr($user->name, 0, 1) }}</div>
                    <img id="avatar-preview" src="" alt="" class="hidden">
                @endif

                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>

            <div class="grid content-start gap-5 sm:grid-cols-2">
                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
                </div>

                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
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
                                  :value="old('phone', $user->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <x-input-label for="gender" value="Jenis Kelamin" class="font-semibold text-slate-700" />
                    </span>
                    <select id="gender" name="gender" style="color-scheme: light;" class="mt-1 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ old('gender', $user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <x-input-error class="mt-1.5" :messages="$errors->get('gender')" />
                </div>
            </div>
        </div>

        @if(Auth::user()->role === 'umum')
        <div>
            <span class="mb-1 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <x-input-label for="bio" value="Bio / Ringkasan Singkat" class="font-semibold text-slate-700" />
            </span>
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                      placeholder="Ceritakan singkat mengenai latar belakang, minat, dan tujuan karir Anda..."
                      maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>
            <div class="flex justify-between mt-1.5">
                <span class="text-xs text-slate-400">Digunakan untuk profil CV lamaran kerja Anda.</span>
                <span class="text-xs text-slate-400">Maks. 500 karakter</span>
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('bio')" />
        </div>

        <div x-data="skillsManager({{ Js::from($user->skills->pluck('name')->toArray()) }})">
            <div class="mb-2 flex items-center justify-between gap-3">
                <span class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <x-input-label value="Keahlian & Kompetensi" class="font-semibold text-slate-700" />
                </span>
                <button type="button" @click="$refs.skillInput.focus()" class="shrink-0 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 transition hover:bg-blue-100">
                    + Tambah Keahlian
                </button>
            </div>
            <p class="text-xs text-slate-400 mb-2">Tulis keahlian lalu tekan <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-mono">Enter</kbd> atau tanda koma <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-mono">,</kbd></p>

            <div class="flex flex-wrap gap-2 p-3 border border-slate-200 rounded-xl min-h-[48px] bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all cursor-text"
                 @click="$refs.skillInput.focus()">
                <template x-for="(skill, i) in skills" :key="i">
                    <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">
                        <span x-text="skill"></span>
                        <button type="button" @click.stop="remove(i)"
                                class="w-4 h-4 rounded-md hover:bg-blue-100 flex items-center justify-center text-blue-500 hover:text-blue-700 transition">
                            &times;
                        </button>
                    </span>
                </template>
                <input x-ref="skillInput"
                       x-model="input"
                       @keydown.enter.prevent="add()"
                       @keydown.188.prevent="add()"
                       @keydown.backspace="backspace()"
                       type="text"
                       placeholder="Tambah keahlian (misal: Excel, Laravel)..."
                       class="flex-1 min-w-[200px] outline-none border-none text-sm text-slate-900 bg-transparent py-0.5 focus:ring-0">
            </div>

            <template x-for="skill in skills" :key="skill">
                <input type="hidden" name="skills[]" :value="skill">
            </template>
        </div>
        @endif

        <div class="border-t border-slate-100 pt-6">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Data Diri & CV</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <x-input-label for="preferred_position" value="Posisi yang Diinginkan" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="preferred_position" name="preferred_position" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('preferred_position', $user->preferred_position ?? '')"
                                  placeholder="Contoh: Frontend Developer" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('preferred_position')" />
                </div>

                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <x-input-label for="birth_place" value="Tempat Lahir" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="birth_place" name="birth_place" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('birth_place', $user->birth_place ?? '')"
                                  placeholder="Contoh: Cikampek" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('birth_place')" />
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <x-input-label for="birth_date" value="Tanggal Lahir" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="birth_date" name="birth_date" type="date" style="color-scheme: light;" class="mt-1 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('birth_date', $user->birth_date?->format('Y-m-d') ?? '')" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('birth_date')" />
                </div>

                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <x-input-label for="address" value="Alamat Tinggal" class="font-semibold text-slate-700" />
                    </span>
                    <textarea id="address" name="address" rows="2"
                              class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                              placeholder="Masukkan alamat domisili lengkap Anda...">{{ old('address', $user->address ?? '') }}</textarea>
                    <x-input-error class="mt-1.5" :messages="$errors->get('address')" />
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                        <x-input-label for="portfolio_url" value="Portofolio / Google Drive Link" class="font-semibold text-slate-700" />
                    </span>
                    <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('portfolio_url', $user->portfolio_url ?? '')"
                                  placeholder="https://namaportofolio.com atau https://drive.google.com/..." />
                    <p class="text-xs text-slate-400 mt-1">Portfolio website atau link Google Drive</p>
                    <x-input-error class="mt-1.5" :messages="$errors->get('portfolio_url')" />
                </div>

                <div>
                    <span class="mb-1 flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <x-input-label for="portfolio_type" value="Tipe Portofolio" class="font-semibold text-slate-700" />
                    </span>
                    <select id="portfolio_type" name="portfolio_type" style="color-scheme: light;" class="mt-1 block w-full rounded-xl border-slate-200 bg-white text-slate-900 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                        <option value="">Pilih tipe portofolio</option>
                        <option value="website" {{ old('portfolio_type', $user->portfolio_type ?? '') == 'website' ? 'selected' : '' }}>Portfolio Website</option>
                        <option value="drive" {{ old('portfolio_type', $user->portfolio_type ?? '') == 'drive' ? 'selected' : '' }}>Google Drive</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Pilih tipe link portofolio Anda</p>
                    <x-input-error class="mt-1.5" :messages="$errors->get('portfolio_type')" />
                </div>
            </div>

            <div class="mt-5">
                <span class="mb-1 flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 01-2.06-2.06 2.06 2.06 0 112.06 2.06zm1.78 13.02H3.56V9h3.56v11.45z"/></svg>
                    <x-input-label for="linkedin_url" value="LinkedIn" class="font-semibold text-slate-700" />
                </span>
                <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                              :value="old('linkedin_url', $user->linkedin_url ?? '')"
                              placeholder="https://linkedin.com/in/namamu" />
                <x-input-error class="mt-1.5" :messages="$errors->get('linkedin_url')" />
            </div>

            <div class="mt-5">
                <span class="mb-1 flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>
                    <x-input-label for="education_history" value="Riwayat Pendidikan (SD s.d. sekarang)" class="font-semibold text-slate-700" />
                </span>
                <div class="mb-2 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-700">
                    Isi riwayat pendidikan Anda dari tingkat paling rendah sampai saat ini. Jika Anda bukan dari SMK MUTU, Anda tetap dapat mengisi sekolah atau lembaga pendidikan terakhir Anda.
                </div>
                <textarea id="education_history" name="education_history" rows="4"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                          placeholder="Contoh:&#10;SD Negeri 1 Cikampek (2016-2022)&#10;SMP Negeri 2 Cikampek (2022-2025)&#10;SMK MUTU Cikampek (2025-sekarang)&#10;Jurusan: Akuntansi">{{ old('education_history', $user->education_history ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('education_history')" />
            </div>

            <div class="mt-5">
                <span class="mb-1 flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <x-input-label for="experience_organization" value="Pengalaman / Organisasi" class="font-semibold text-slate-700" />
                </span>
                <textarea id="experience_organization" name="experience_organization" rows="4"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                          placeholder="Contoh: Magang di toko online&#10;Ketua OSIS&#10;Anggota Pramuka">{{ old('experience_organization', $user->experience_organization ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('experience_organization')" />
            </div>
        </div>

        <div class="sticky bottom-4 z-10 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
            <p class="hidden items-center gap-1.5 text-xs text-slate-400 sm:flex">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pastikan data sudah benar sebelum menyimpan perubahan.
            </p>
            <div class="flex items-center gap-3">
            <x-ui.btn type="submit">
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
                    Profil berhasil diperbarui.
                </p>
            @endif
            </div>
        </div>
    </form>
</section>

{{-- Cropper Modal --}}
<div id="cropperModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Sesuaikan Foto Profil</h3>
        <div class="max-h-[60vh] overflow-hidden rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200">
            <img id="imageToCrop" src="" class="max-w-full hidden">
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="closeCropper()" class="px-5 py-2.5 text-slate-600 font-medium hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">Batal</button>
            <button type="button" onclick="applyCrop()" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl shadow-sm hover:bg-blue-700 hover:shadow transition">Simpan Potongan</button>
        </div>
    </div>
</div>

<script>
let cropper = null;

function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const modal = document.getElementById('cropperModal');
        const img = document.getElementById('imageToCrop');
        img.src = e.target.result;
        img.classList.remove('hidden');
        modal.classList.remove('hidden');
        
        if (cropper) {
            cropper.destroy();
        }
        cropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.85,
            cropBoxMovable: true,
            cropBoxResizable: true,
            guides: true,
            center: true,
            highlight: false,
            zoomable: true,
            scalable: true,
            background: false,
        });
    };
    reader.readAsDataURL(file);
    // Reset file input so picking the same file again triggers change event
    event.target.value = '';
}

function closeCropper() {
    document.getElementById('cropperModal').classList.add('hidden');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function applyCrop() {
    if (!cropper) return;
    cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob((blob) => {
        const file = new File([blob], 'avatar_cropped.webp', { type: 'image/webp' });
        
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('avatar').files = dt.files;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgPreview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-preview-placeholder');
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
        
        closeCropper();
    }, 'image/webp');
}

function skillsManager(initial) {
    return {
        skills: Array.isArray(initial) ? [...initial] : [],
        input: '',
        add() {
            const val = this.input.trim().replace(/,/g, '');
            if (val.length > 0 && val.length <= 50 && !this.skills.includes(val)) {
                this.skills.push(val);
            }
            this.input = '';
        },
        remove(i) {
            this.skills.splice(i, 1);
        },
        backspace() {
            if (this.input === '' && this.skills.length > 0) {
                this.skills.pop();
            }
        }
    };
}
</script>
