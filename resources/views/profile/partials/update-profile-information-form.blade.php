<section>
    <header class="border-b border-slate-100 pb-4 mb-6">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-500">Perbarui foto, nama, email, nomor HP, keahlian, dan info akademik Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Avatar Section --}}
        <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100 flex flex-col sm:flex-row items-center gap-5">
            <div class="relative group">
                @if($user->avatar)
                    <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-2xl object-cover border-2 border-white shadow-md ring-1 ring-slate-200">
                @else
                    <div id="avatar-preview-placeholder"
                         class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-2xl font-extrabold shadow-md">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <img id="avatar-preview" src="" alt="" class="w-20 h-20 rounded-2xl object-cover border-2 border-white shadow-md ring-1 ring-slate-200 hidden">
                @endif
            </div>
            
            <div class="text-center sm:text-left">
                <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:text-slate-900 shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pilih Foto Baru
                </label>
                <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp"
                       onchange="previewAvatar(event)">
                <p class="text-xs text-slate-400 mt-2">Format JPG, PNG, atau WebP. Maksimal 2MB.</p>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        {{-- Grid Input Nama & Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-700 font-semibold mb-1" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-700 font-semibold mb-1" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                              :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-slate-800">
                            {{ __('Alamat email Anda belum diverifikasi.') }}
                            <button form="send-verification" class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Kirim ulang email verifikasi.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">Link verifikasi baru telah dikirim.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Nomor HP --}}
        <div>
            <x-input-label for="phone" value="Nomor Handphone" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                          :value="old('phone', $user->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
        </div>

        {{-- Bio --}}
        <div>
            <x-input-label for="bio" value="Bio / Ringkasan Singkat" class="text-slate-700 font-semibold mb-1" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm"
                      placeholder="Ceritakan singkat mengenai latar belakang, minat, dan tujuan karir Anda..."
                      maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>
            <div class="flex justify-between mt-1.5">
                <span class="text-xs text-slate-400">Digunakan untuk profil CV lamaran kerja Anda.</span>
                <span class="text-xs text-slate-400">Maks. 500 karakter</span>
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('bio')" />
        </div>

        {{-- Keahlian --}}
        <div x-data="skillsManager({{ Js::from($user->skills->pluck('name')->toArray()) }})">
            <x-input-label value="Keahlian & Kompetensi" class="text-slate-700 font-semibold mb-1" />
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
                       class="flex-1 min-w-[200px] outline-none border-none text-sm bg-transparent py-0.5 focus:ring-0">
            </div>

            <template x-for="skill in skills" :key="skill">
                <input type="hidden" name="skills[]" :value="skill">
            </template>
        </div>

        {{-- Data Akademik (student/alumni only) --}}
        @if(in_array(Auth::user()->role, ['student', 'alumni']))
        <div class="border-t border-slate-100 pt-6">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Informasi Akademik</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="major" value="Jurusan / Program Keahlian" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="major" name="major" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                  :value="old('major', $user->student->major ?? '')"
                                  placeholder="Contoh: Teknik Komputer & Jaringan" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('major')" />
                </div>

                <div>
                    <x-input-label for="graduation_year" value="Tahun Lulus" class="text-slate-700 font-semibold mb-1" />
                    <select id="graduation_year" name="graduation_year"
                            class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                        <option value="">Pilih Tahun Lulus</option>
                        @for($year = date('Y') + 2; $year >= date('Y') - 15; $year--)
                            <option value="{{ $year }}"
                                {{ old('graduation_year', $user->student->graduation_year ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    <x-input-error class="mt-1.5" :messages="$errors->get('graduation_year')" />
                </div>
            </div>

            <div class="mt-5">
                <x-input-label for="address" value="Alamat Tinggal" class="text-slate-700 font-semibold mb-1" />
                <textarea id="address" name="address" rows="2"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm"
                          placeholder="Masukkan alamat domisili lengkap Anda...">{{ old('address', $user->student->address ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('address')" />
            </div>
        </div>
        @endif

        <div class="flex items-center gap-4 border-t border-slate-100 pt-6">
            <x-primary-button class="rounded-xl px-5 py-2.5 bg-blue-600 hover:bg-blue-700 shadow-sm transition">{{ __('Simpan Perubahan') }}</x-primary-button>

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
    </form>
</section>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-preview-placeholder');
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
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
