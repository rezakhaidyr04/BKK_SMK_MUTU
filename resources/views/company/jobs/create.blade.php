<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Buat Lowongan" subtitle="Tambah lowongan baru untuk perusahaan Anda." eyebrow="Perusahaan › Lowongan">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Lowongan Baru
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    Perusahaan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary">Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section max-w-5xl mx-auto">
            @if($errors->any())
                <x-ui.alert type="danger" class="mb-6">
                    <div class="space-y-2">
                        <p class="font-semibold">Ada beberapa kesalahan pada formulir.</p>
                        <ul class="mt-2 list-disc pl-5 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </x-ui.alert>
            @endif

            <form method="POST" action="{{ route('company.jobs.store') }}" class="grid gap-6">
                @csrf
                <input type="hidden" name="company_name" value="{{ auth()->user()->company?->name ?? '' }}">

                {{-- Penanda langkah pengisian --}}
                <div data-reveal class="bg-white rounded-2xl border border-slate-200 shadow-sm px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold flex items-center justify-center shadow flex-shrink-0">1</span>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-slate-900 leading-tight">Informasi Lowongan</p>
                                <p class="text-[11px] text-slate-400">Detail dasar</p>
                            </div>
                        </div>
                        <div class="flex-1 h-0.5 rounded-full bg-slate-200 mx-1"></div>
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-full bg-white border-2 border-slate-200 text-slate-400 text-sm font-bold flex items-center justify-center flex-shrink-0">2</span>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-slate-500 leading-tight">Rincian Lowongan</p>
                                <p class="text-[11px] text-slate-400">Kualifikasi & deskripsi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <x-ui.panel title="Informasi Lowongan" subtitle="Lengkapi detail dasar lowongan terlebih dahulu." class="job-form-panel job-form-panel-primary" data-reveal>
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">1</span>
                    </x-slot>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="ui-label">Judul Lowongan <span class="text-red-600">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="cth: Junior Web Developer" class="ui-input">
                            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Posisi</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="ui-input">
                            @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-5"><x-job-location-fields :province="old('province')" :city="old('city')" :district="old('district')" /></div>
                        <div>
                            <label class="ui-label">Tipe Kerja</label>
                            <select name="job_type" class="ui-select">
                                <option value="full_time" {{ old('job_type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                <option value="part_time" {{ old('job_type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                <option value="internship" {{ old('job_type') === 'internship' ? 'selected' : '' }}>Magang</option>
                                <option value="contract" {{ old('job_type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('job_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Minimum</label>
                            <div class="group flex">
                                <span class="inline-flex items-center px-3.5 rounded-l-xl border-[1.5px] border-r-0 border-slate-200 bg-slate-50 text-sm font-bold text-slate-500 transition-colors group-focus-within:border-blue-500 group-focus-within:bg-blue-50 group-focus-within:text-blue-600">Rp</span>
                                <input type="number" name="salary_min" value="{{ old('salary_min') }}" min="0" placeholder="3000000" class="ui-input salary-rupiah" data-salary-input="salary_min" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Pratinjau: <span class="font-semibold text-slate-600" data-salary-preview="salary_min">–</span></p>
                            @error('salary_min')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Maksimum</label>
                            <div class="group flex">
                                <span class="inline-flex items-center px-3.5 rounded-l-xl border-[1.5px] border-r-0 border-slate-200 bg-slate-50 text-sm font-bold text-slate-500 transition-colors group-focus-within:border-blue-500 group-focus-within:bg-blue-50 group-focus-within:text-blue-600">Rp</span>
                                <input type="number" name="salary_max" value="{{ old('salary_max') }}" min="0" placeholder="5000000" class="ui-input salary-rupiah" data-salary-input="salary_max" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Pratinjau: <span class="font-semibold text-slate-600" data-salary-preview="salary_max">–</span></p>
                            @error('salary_max')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" class="ui-input">
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="rounded-xl bg-green-50 border border-green-100 p-4">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm leading-relaxed text-green-800">Perusahaan Anda sudah <strong>terverifikasi</strong>, lowongan yang dibuat akan <strong>langsung dipublikasikan</strong> tanpa menunggu persetujuan admin.</p>
                            </div>
                        </div>
                    </div>
                </x-ui.panel>

                <x-ui.panel title="Rincian Lowongan" subtitle="Tambahkan kualifikasi, benefit, dan deskripsi lengkap pekerjaan." class="job-form-panel job-form-panel-secondary" data-reveal>
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">2</span>
                    </x-slot>
                    <div class="grid gap-5">
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="ui-label">Pendidikan Minimal</label>
                                <input type="text" name="education" value="{{ old('education') }}" placeholder="cth: SMK / D3 / S1" class="ui-input">
                                @error('education')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Pengalaman</label>
                                <input type="text" name="experience" value="{{ old('experience') }}" placeholder="cth: 1 - 2 Tahun / Fresh Graduate" class="ui-input">
                                @error('experience')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Jenis Kelamin</label>
                                <input type="text" name="gender" value="{{ old('gender') }}" placeholder="cth: Laki-laki / Perempuan / Keduanya" class="ui-input">
                                @error('gender')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Rentang Usia</label>
                                <input type="text" name="age_range" value="{{ old('age_range') }}" placeholder="cth: 18 - 25 Tahun" class="ui-input">
                                @error('age_range')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="ui-label">Jam Kerja</label>
                                <input type="text" name="work_hours" value="{{ old('work_hours') }}" placeholder="cth: Senin - Jumat (08.00 - 17.00)" class="ui-input">
                                @error('work_hours')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="ui-label">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" placeholder="cth: Pendidikan min. SMK, mampu bekerja dalam tim…" class="ui-textarea">{{ old('qualifications') }}</textarea>
                            @error('qualifications')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Benefit <span class="text-slate-400 font-normal">(otomatis dinomori — cukup ketik biasa)</span></label>
                            <textarea name="benefits" rows="4" data-autonumber placeholder="cth:&#10;1. BPJS Kesehatan dan Ketenagakerjaan&#10;2. Uang makan dan transport&#10;3. THR" class="ui-textarea">{{ old('benefits') }}</textarea>
                            <p class="mt-1.5 text-xs text-slate-400">Ketik biasa pakai koma/enter — nomor muncul sendiri saat field ditinggalkan.</p>
                            @error('benefits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="6" placeholder="Jelaskan tugas dan tanggung jawab pekerjaan…" class="ui-textarea">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <div class="job-form-actions sticky bottom-4 z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end mt-4">
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" class="w-full sm:w-auto">Batal</x-ui.btn>
                    <x-ui.btn type="submit" variant="company" class="w-full sm:w-auto">Simpan Lowongan</x-ui.btn>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        /* Hilangkan spinner jelek bawaan browser di input angka gaji */
        input.salary-rupiah::-webkit-outer-spin-button,
        input.salary-rupiah::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input.salary-rupiah {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Pratinjau nominal gaji ala Rupiah saat mengetik (tampilan saja, nilai asli tetap angka)
        (function () {
            function formatRp(v) {
                if (v === '' || v === null || isNaN(Number(v))) return '–';
                return 'Rp ' + Number(v).toLocaleString('id-ID');
            }
            document.querySelectorAll('[data-salary-input]').forEach(function (input) {
                var key = input.getAttribute('data-salary-input');
                var out = document.querySelector('[data-salary-preview="' + key + '"]');
                if (!out) return;
                var update = function () { out.textContent = formatRp(input.value); };
                input.addEventListener('input', update);
                update();
            });
        })();

        // Benefit otomatis dinomori: ketik "a, b, c" → blur → "1. a\n2. b\n3. c".
        // (Server juga menormalisasi ulang saat disimpan sebagai pengaman.)
        (function () {
            function autonumber(text) {
                var items = (text || '').split(/[\r\n,;]+/).map(function (s) { return s.trim(); })
                    .filter(Boolean)
                    .map(function (s) { return s.replace(/^(\d+[.)\-:]|[-•*])\s*/u, ''); });
                if (items.length > 1) {
                    return items.map(function (s, i) { return (i + 1) + '. ' + s; }).join('\n');
                }
                return items[0] || '';
            }
            document.querySelectorAll('textarea[data-autonumber]').forEach(function (ta) {
                // Tekan Enter → baris baru langsung diawali nomor berikutnya.
                ta.addEventListener('keydown', function (e) {
                    if (e.key !== 'Enter') return;
                    e.preventDefault();
                    var start = ta.selectionStart, end = ta.selectionEnd;
                    var before = ta.value.slice(0, start);
                    var lineStart = before.lastIndexOf('\n') + 1;
                    var m = before.slice(lineStart).match(/^\s*(\d+)[.)\-:]/);
                    var next = m ? (parseInt(m[1], 10) + 1) : 1;
                    if (typeof ta.setRangeText === 'function') {
                        ta.setRangeText('\n' + next + '. ', start, end, 'end');
                    } else {
                        ta.value = before + '\n' + next + '. ' + ta.value.slice(end);
                    }
                    ta.dispatchEvent(new Event('input'));
                });
                ta.addEventListener('blur', function () { ta.value = autonumber(ta.value); });
                var form = ta.closest('form');
                if (form) form.addEventListener('submit', function () { ta.value = autonumber(ta.value); });
            });
        })();
    </script>
    @endpush
</x-app-layout>

