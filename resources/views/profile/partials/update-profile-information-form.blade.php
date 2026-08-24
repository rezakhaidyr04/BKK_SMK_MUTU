<section>
    <header class="border-b border-slate-100 pb-4 mb-6">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-500">
            @if(Auth::user()->role === 'jobseeker')
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

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Avatar Section --}}
        <div class="bg-slate-50/50 rounded-2xl p-4 border border-slate-100">
            @php
                $avatarPreviewUrl = $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null;
            @endphp

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Foto Profil</h3>
                    <p class="text-xs text-slate-400 mt-1">Foto utama ditampilkan pada kartu profil di bagian atas halaman.</p>
                </div>

                <div class="text-left sm:text-right">
                    <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:text-slate-900 shadow-sm transition">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Pilih Foto Baru
                    </label>
                    <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp,image/gif"
                           onchange="previewAvatar(event)">
                    <p class="text-xs text-slate-400 mt-2">Format file JPG, PNG, WebP, GIF. Maksimal 3MB.</p>
                </div>
            </div>

            @if($avatarPreviewUrl)
                <img id="avatar-preview" src="{{ $avatarPreviewUrl }}" alt="" class="hidden">
            @else
                <div id="avatar-preview-placeholder" class="hidden">{{ substr($user->name, 0, 1) }}</div>
                <img id="avatar-preview" src="" alt="" class="hidden">
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        {{-- Grid Input Nama & Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-700 font-semibold mb-1" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-700 font-semibold mb-1" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
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
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                          :value="old('phone', $user->phone)" autocomplete="tel" placeholder="Contoh: 08123456789" />
            <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
        </div>

        {{-- Bio / Ringkasan Singkat --}}
        @if(Auth::user()->role === 'jobseeker')
        <div>
            <x-input-label for="bio" value="Bio / Ringkasan Singkat" class="text-slate-700 font-semibold mb-1" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                      placeholder="Ceritakan singkat mengenai latar belakang, minat, dan tujuan karir Anda..."
                      maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>
            <div class="flex justify-between mt-1.5">
                <span class="text-xs text-slate-400">Digunakan untuk profil CV lamaran kerja Anda.</span>
                <span class="text-xs text-slate-400">Maks. 500 karakter</span>
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('bio')" />
        </div>

        {{-- Keahlian (jobseeker only) --}}
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
        @endif

        {{-- Data Diri & CV --}}
        <div class="border-t border-slate-100 pt-6">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Data Diri & CV</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="preferred_position" value="Posisi yang Diinginkan" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="preferred_position" name="preferred_position" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  :value="old('preferred_position', $user->student->preferred_position ?? '')"
                                  placeholder="Contoh: Frontend Developer" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('preferred_position')" />
                </div>

                <div>
                    <x-input-label for="gender" value="Jenis Kelamin" class="text-slate-700 font-semibold mb-1" />
                    <select id="gender" name="gender" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ old('gender', $user->student->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender', $user->student->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <x-input-error class="mt-1.5" :messages="$errors->get('gender')" />
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="birth_place" value="Tempat Lahir" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="birth_place" name="birth_place" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  :value="old('birth_place', $user->student->birth_place ?? '')"
                                  placeholder="Contoh: Cikampek" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('birth_place')" />
                </div>

                <div>
                    <x-input-label for="birth_date" value="Tanggal Lahir" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  :value="old('birth_date', $user->student->birth_date ?? '')" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('birth_date')" />
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="linkedin_url" value="LinkedIn" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  :value="old('linkedin_url', $user->student->linkedin_url ?? '')"
                                  placeholder="https://linkedin.com/in/namamu" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('linkedin_url')" />
                </div>

                <div>
                    <x-input-label for="portfolio_url" value="Portofolio / Website" class="text-slate-700 font-semibold mb-1" />
                    <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  :value="old('portfolio_url', $user->student->portfolio_url ?? '')"
                                  placeholder="https://namaportofolio.com" />
                    <x-input-error class="mt-1.5" :messages="$errors->get('portfolio_url')" />
                </div>
            </div>

            <div class="mt-5">
                <x-input-label for="education_history" value="Riwayat Pendidikan (SD s.d. sekarang)" class="text-slate-700 font-semibold mb-1" />
                <div class="mb-2 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-700">
                    Isi riwayat pendidikan Anda dari tingkat paling rendah sampai saat ini. Jika Anda bukan dari SMK MUTU, Anda tetap dapat mengisi sekolah atau lembaga pendidikan terakhir Anda.
                </div>
                <textarea id="education_history" name="education_history" rows="4"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                          placeholder="Contoh:&#10;SD Negeri 1 Cikampek (2016-2022)&#10;SMP Negeri 2 Cikampek (2022-2025)&#10;SMK MUTU Cikampek (2025-sekarang)&#10;Jurusan: Akuntansi">{{ old('education_history', $user->student->education_history ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('education_history')" />
            </div>

            <div class="mt-5">
                <x-input-label for="experience_organization" value="Pengalaman / Organisasi" class="text-slate-700 font-semibold mb-1" />
                <textarea id="experience_organization" name="experience_organization" rows="4"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                          placeholder="Contoh: Magang di toko online&#10;Ketua OSIS&#10;Anggota Pramuka">{{ old('experience_organization', $user->student->experience_organization ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('experience_organization')" />
            </div>

            <div class="mt-5">
                <x-input-label for="address" value="Alamat Tinggal" class="text-slate-700 font-semibold mb-1" />
                <textarea id="address" name="address" rows="2"
                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                          placeholder="Masukkan alamat domisili lengkap Anda...">{{ old('address', $user->student->address ?? '') }}</textarea>
                <x-input-error class="mt-1.5" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center gap-4 border-t border-slate-100 pt-6">
            <x-ui.btn type="submit">{{ __('Simpan Perubahan') }}</x-ui.btn>

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

{{-- Cropper Modal --}}
<div id="cropperModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Sesuaikan Foto Profil</h3>
        <div class="max-h-[60vh] overflow-hidden rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200">
            <img id="imageToCrop" src="" class="max-w-full hidden">
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="closeCropper()" class="px-5 py-2.5 text-slate-600 font-medium hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">Batal</button>
            <button type="button" onclick="applyCrop()" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl shadow-sm hover:bg-indigo-700 hover:shadow transition">Simpan Potongan</button>
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
            autoCropArea: 1,
            cropBoxMovable: false,
            cropBoxResizable: false,
            guides: false,
            center: false,
            highlight: false,
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