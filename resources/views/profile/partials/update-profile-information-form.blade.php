<section>
    <header>
        <h2 class="text-base font-semibold text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui foto, nama, email, nomor HP, dan bio Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        {{-- Avatar --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div class="relative">
                    @if($user->avatar)
                        <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}"
                             alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div id="avatar-preview-placeholder"
                             class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <img id="avatar-preview" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 hidden">
                    @endif
                </div>
                <div>
                    <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Ganti Foto
                    </label>
                    <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp"
                           onchange="previewAvatar(event)">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP &middot; maks. 2MB</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        {{-- Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">Link verifikasi baru telah dikirim.</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Nomor HP --}}
        <div>
            <x-input-label for="phone" value="Nomor HP" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                          :value="old('phone', $user->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Bio --}}
        <div>
            <x-input-label for="bio" value="Bio / Ringkasan Diri" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                      placeholder="Ceritakan singkat tentang diri Anda, minat, dan tujuan karir..."
                      maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>
            <p class="mt-1 text-xs text-gray-400">Maksimal 500 karakter. Digunakan di CV Anda.</p>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        {{-- Keahlian --}}
        <div x-data="skillsManager({{ Js::from($user->skills->pluck('name')->toArray()) }})">
            <x-input-label value="Keahlian" />
            <p class="text-xs text-gray-400 mb-2">Tekan <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Enter</kbd> atau <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">,</kbd> untuk menambah. Klik × untuk hapus.</p>

            {{-- Tag container + input dalam satu kotak --}}
            <div class="flex flex-wrap gap-1.5 p-2.5 border border-gray-300 rounded-md min-h-[44px] bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent cursor-text"
                 @click="$refs.skillInput.focus()">
                <template x-for="(skill, i) in skills" :key="i">
                    <span class="inline-flex items-center gap-1 pl-2.5 pr-1 py-0.5 bg-blue-100 text-blue-800 text-sm rounded-full">
                        <span x-text="skill"></span>
                        <button type="button" @click.stop="remove(i)"
                                class="w-4 h-4 rounded-full hover:bg-blue-200 flex items-center justify-center text-blue-600 hover:text-blue-800 transition">
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
                       placeholder="Contoh: Microsoft Excel, AutoCAD, Desain Grafis..."
                       class="flex-1 min-w-[180px] outline-none border-none text-sm bg-transparent py-0.5">
            </div>

            {{-- Hidden inputs untuk dikirim bersama form --}}
            <template x-for="skill in skills" :key="skill">
                <input type="hidden" name="skills[]" :value="skill">
            </template>
        </div>

        {{-- Data Akademik (student/alumni only) --}}
        @if(in_array(Auth::user()->role, ['student', 'alumni']))
        <div class="border-t border-gray-100 pt-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Data Akademik</h3>
            <div class="space-y-4">
                <div>
                    <x-input-label for="major" value="Jurusan / Program Keahlian" />
                    <x-text-input id="major" name="major" type="text" class="mt-1 block w-full"
                                  :value="old('major', $user->student->major ?? '')"
                                  placeholder="Contoh: Teknik Komputer dan Jaringan" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('major')" />
                </div>

                <div>
                    <x-input-label for="graduation_year" value="Tahun Lulus" />
                    <select id="graduation_year" name="graduation_year"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Pilih tahun lulus</option>
                        @for($year = date('Y') + 2; $year >= date('Y') - 15; $year--)
                            <option value="{{ $year }}"
                                {{ old('graduation_year', $user->student->graduation_year ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    <x-input-error class="mt-1.5" :messages="$errors->get('graduation_year')" />
                </div>

                <div>
                    <x-input-label for="address" value="Alamat Tinggal" />
                    <textarea id="address" name="address" rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Kelurahan, Kecamatan, Kota/Kabupaten">{{ old('address', $user->student->address ?? '') }}</textarea>
                    <x-input-error class="mt-1.5" :messages="$errors->get('address')" />
                </div>
            </div>
        </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-green-600 font-medium">
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
            // Jika input kosong dan tekan backspace, hapus tag terakhir
            if (this.input === '' && this.skills.length > 0) {
                this.skills.pop();
            }
        }
    };
}
</script>
