{{-- Form data karier bersama: dipakai halaman Pembuat CV (sumber: kolom users). --}}
<div>
    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-1">Data Karier</h3>
    <p class="text-xs text-slate-400 mb-4">Tersimpan ke profil Anda dan dipakai otomatis saat membuat CV.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <span class="mb-1 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <x-input-label for="preferred_position" value="Posisi yang Diinginkan" class="font-semibold text-slate-700" />
            </span>
            <x-text-input id="preferred_position" name="preferred_position" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                           :value="old('preferred_position', $user->preferred_position ?? '')"
                           placeholder="Contoh: Operator Produksi" />
            <p class="text-xs text-slate-400 mt-1">Dipakai untuk rekomendasi lowongan yang cocok untukmu.</p>
            <x-input-error class="mt-1.5" :messages="$errors->get('preferred_position')" />
        </div>
    </div>

    <div class="mt-5">
        <span class="mb-1 flex items-center gap-1.5">
            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <x-input-label for="bio" value="Bio / Ringkasan Singkat" class="font-semibold text-slate-700" />
        </span>
        <textarea id="bio" name="bio" rows="3"
                  class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                  placeholder="Ceritakan singkat mengenai latar belakang, minat, dan tujuan karir Anda..."
                  maxlength="500">{{ old('bio', isset($user->bio) ? str_replace(['\\r\\n', '\\n', '\\r'], "\n", $user->bio) : '') }}</textarea>
        <div class="flex justify-between mt-1.5">
            <span class="text-xs text-slate-400">Dipakai untuk profil CV lamaran kerja Anda.</span>
            <span class="text-xs text-slate-400">Maks. 500 karakter</span>
        </div>
        <x-input-error class="mt-1.5" :messages="$errors->get('bio')" />
    </div>

    <div class="mt-5" x-data="skillsManager({{ Js::from($user->skills->pluck('name')->toArray()) }})">
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
        <textarea id="education_history" name="education_history" rows="4"
                  class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                  placeholder="Contoh:&#10;SD Negeri 1 Cikampek (2016-2022)&#10;SMP Negeri 2 Cikampek (2022-2025)&#10;SMK TI Muhammadiyah Cikampek (2025-sekarang)&#10;Jurusan: Akuntansi">{{ old('education_history', isset($user->education_history) ? str_replace(['\\r\\n', '\\n', '\\r'], "\n", $user->education_history) : '') }}</textarea>
        <x-input-error class="mt-1.5" :messages="$errors->get('education_history')" />
    </div>

    <div class="mt-5">
        <span class="mb-1 flex items-center gap-1.5">
            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <x-input-label for="experience_organization" value="Pengalaman / Organisasi" class="font-semibold text-slate-700" />
        </span>
        <textarea id="experience_organization" name="experience_organization" rows="4"
                  class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm text-slate-900 bg-white"
                   placeholder="Contoh: Magang di toko online&#10;Ketua OSIS&#10;Anggota Pramuka">{{ old('experience_organization', isset($user->experience_organization) ? str_replace(['\\r\\n', '\\n', '\\r'], "\n", $user->experience_organization) : '') }}</textarea>
        <x-input-error class="mt-1.5" :messages="$errors->get('experience_organization')" />
    </div>
</div>

<script>
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
