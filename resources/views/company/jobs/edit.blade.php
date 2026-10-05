<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Edit Lowongan" subtitle="Perbarui detail lowongan milik perusahaan Anda." eyebrow="Perusahaan › Lowongan">
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

            <form method="POST" action="{{ route('company.jobs.update', $job) }}" class="grid gap-6">
                @csrf
                @method('PUT')

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

                <x-ui.panel title="Informasi Lowongan" subtitle="Status dan kepemilikan tidak dapat diubah dari formulir ini." class="job-form-panel job-form-panel-primary" data-reveal>
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">1</span>
                    </x-slot>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="ui-label">Judul Lowongan <span class="text-red-600">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="ui-input">
                            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Posisi</label>
                            <input type="text" name="position" value="{{ old('position', $job->position) }}" class="ui-input">
                            @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-5"><x-job-location-fields :province="old('province', $job->province)" :city="old('city', $job->city)" :district="old('district', $job->district)" /></div>
                        <div>
                            <label class="ui-label">Tipe Kerja</label>
                            <select name="job_type" class="ui-select">
                                @foreach(['full_time' => 'Penuh Waktu', 'part_time' => 'Paruh Waktu', 'internship' => 'Magang', 'contract' => 'Kontrak'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('job_type', $job->job_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('job_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Minimum</label>
                            <div class="group flex">
                                <span class="inline-flex items-center px-3.5 rounded-l-xl border-[1.5px] border-r-0 border-slate-200 bg-slate-50 text-sm font-bold text-slate-500 transition-colors group-focus-within:border-blue-500 group-focus-within:bg-blue-50 group-focus-within:text-blue-600">Rp</span>
                                <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" min="0" placeholder="3000000" class="ui-input salary-rupiah" data-salary-input="salary_min" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Pratinjau: <span class="font-semibold text-slate-600" data-salary-preview="salary_min">–</span></p>
                            @error('salary_min')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Maksimum</label>
                            <div class="group flex">
                                <span class="inline-flex items-center px-3.5 rounded-l-xl border-[1.5px] border-r-0 border-slate-200 bg-slate-50 text-sm font-bold text-slate-500 transition-colors group-focus-within:border-blue-500 group-focus-within:bg-blue-50 group-focus-within:text-blue-600">Rp</span>
                                <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" min="0" placeholder="5000000" class="ui-input salary-rupiah" data-salary-input="salary_max" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                            <p class="mt-1.5 text-xs text-slate-400">Pratinjau: <span class="font-semibold text-slate-600" data-salary-preview="salary_max">–</span></p>
                            @error('salary_max')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="ui-input">
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                            Status saat ini: <strong>{{ $job->status }}</strong>. Untuk menayangkan draf, gunakan tombol <strong>Publikasikan</strong> di halaman Lowongan Saya; untuk menutup, gunakan Tutup Lowongan.
                        </div>
                    </div>
                </x-ui.panel>

                <x-ui.panel title="Rincian Lowongan" class="job-form-panel job-form-panel-secondary" data-reveal>
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">2</span>
                    </x-slot>
                    <div class="grid gap-5">
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="ui-label">Pendidikan Minimal</label>
                                <input type="text" name="education" value="{{ old('education', $job->education) }}" placeholder="cth: SMK / D3 / S1" class="ui-input">
                                @error('education')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Pengalaman</label>
                                <input type="text" name="experience" value="{{ old('experience', $job->experience) }}" placeholder="cth: 1 - 2 Tahun / Fresh Graduate" class="ui-input">
                                @error('experience')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Jenis Kelamin</label>
                                <input type="text" name="gender" value="{{ old('gender', $job->gender) }}" placeholder="cth: Laki-laki / Perempuan / Keduanya" class="ui-input">
                                @error('gender')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="ui-label">Rentang Usia</label>
                                <input type="text" name="age_range" value="{{ old('age_range', $job->age_range) }}" placeholder="cth: 18 - 25 Tahun" class="ui-input">
                                @error('age_range')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="ui-label">Jam Kerja</label>
                                <input type="text" name="work_hours" value="{{ old('work_hours', $job->work_hours) }}" placeholder="cth: Senin - Jumat (08.00 - 17.00)" class="ui-input">
                                @error('work_hours')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="ui-label">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" class="ui-textarea">{{ old('qualifications', $job->qualifications) }}</textarea>
                            @error('qualifications')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Benefit <span class="text-slate-400 font-normal">(otomatis dinomori — cukup ketik biasa)</span></label>
                            <textarea name="benefits" rows="4" data-autonumber class="ui-textarea">{{ old('benefits', $job->benefits) }}</textarea>
                            <p class="mt-1.5 text-xs text-slate-400">Ketik biasa pakai koma/enter — nomor muncul sendiri saat field ditinggalkan.</p>
                            @error('benefits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="6" class="ui-textarea">{{ old('description', $job->description) }}</textarea>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <div class="job-form-actions sticky bottom-4 z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end mt-4">
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" class="w-full sm:w-auto">Batal</x-ui.btn>
                    <x-ui.btn type="submit" variant="company" class="w-full sm:w-auto">Simpan Perubahan</x-ui.btn>
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


