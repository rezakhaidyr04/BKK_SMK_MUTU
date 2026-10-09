<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Tracer Study" subtitle="Bantu sekolah memetakan kabar alumni — cukup isi sekali, bisa diperbarui kapan saja." eyebrow="Dashboard › Tracer Study">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ $tracer?->filled_at ? 'Sudah terisi · ' . $tracer->filled_at->format('d M Y') : 'Belum terisi' }}
                </span>
                <span class="page-banner__chip">Pencari Kerja</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section">
            <div class="max-w-2xl mx-auto">
                <x-ui.form-errors />

                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('tracer.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    @csrf

                    <div class="mb-5">
                        <label for="status_kerja" class="block text-sm font-semibold text-gray-900 mb-2">Status Anda saat ini <span class="text-red-500">*</span></label>
                        <select name="status_kerja" id="status_kerja" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_kerja') border-red-500 @enderror">
                            <option value="">— Pilih —</option>
                            @foreach(['bekerja' => 'Bekerja', 'kuliah' => 'Kuliah / Lanjut Studi', 'wirausaha' => 'Wirausaha', 'menganggur' => 'Belum bekerja'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status_kerja', $tracer->status_kerja ?? '') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status_kerja')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label for="company_name" class="block text-sm font-semibold text-gray-900 mb-2">Perusahaan / Kampus / Usaha</label>
                            <input type="text" name="company_name" id="company_name" maxlength="150"
                                value="{{ old('company_name', $tracer->company_name ?? '') }}"
                                placeholder="cth: PT Maju Bersama"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('company_name') border-red-500 @enderror">
                            @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="position" class="block text-sm font-semibold text-gray-900 mb-2">Jabatan / Prodi / Bidang</label>
                            <input type="text" name="position" id="position" maxlength="100"
                                value="{{ old('position', $tracer->position ?? '') }}"
                                placeholder="cth: Operator Produksi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('position') border-red-500 @enderror">
                            @error('position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label for="salary_range" class="block text-sm font-semibold text-gray-900 mb-2">Rentang penghasilan / bulan</label>
                            <select name="salary_range" id="salary_range"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('salary_range') border-red-500 @enderror">
                                <option value="">— Pilih —</option>
                                @foreach(['<3jt' => '< Rp 3 jt', '3-5jt' => 'Rp 3 – 5 jt', '5-10jt' => 'Rp 5 – 10 jt', '>10jt' => '> Rp 10 jt', 'rahasia' => 'Rahasia'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('salary_range', $tracer->salary_range ?? '') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('salary_range')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="is_relevant" class="block text-sm font-semibold text-gray-900 mb-2">Sesuai jurusan SMK?</label>
                            <select name="is_relevant" id="is_relevant"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('is_relevant') border-red-500 @enderror">
                                <option value="">— Pilih —</option>
                                <option value="1" @selected((string) old('is_relevant', isset($tracer->is_relevant) ? (int) $tracer->is_relevant : '') === '1')>Ya, sesuai</option>
                                <option value="0" @selected((string) old('is_relevant', isset($tracer->is_relevant) ? (int) $tracer->is_relevant : '') === '0')>Tidak sesuai</option>
                            </select>
                            @error('is_relevant')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label for="tahun_lulus" class="block text-sm font-semibold text-gray-900 mb-2">Tahun lulus</label>
                            <input type="number" name="tahun_lulus" id="tahun_lulus" min="2000" max="{{ date('Y') }}"
                                value="{{ old('tahun_lulus', $tracer->tahun_lulus ?? '') }}"
                                placeholder="{{ date('Y') - 1 }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_lulus') border-red-500 @enderror">
                            @error('tahun_lulus')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="jurusan" class="block text-sm font-semibold text-gray-900 mb-2">Jurusan</label>
                            <input type="text" name="jurusan" id="jurusan" maxlength="100" list="jurusan-list"
                                value="{{ old('jurusan', $tracer->jurusan ?? '') }}"
                                placeholder="cth: RPL"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jurusan') border-red-500 @enderror">
                            <datalist id="jurusan-list">
                                <option value="RPL"></option>
                                <option value="TKJ"></option>
                                <option value="TBSM"></option>
                                <option value="OTKP"></option>
                                <option value="AKL"></option>
                            </datalist>
                            @error('jurusan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="no_wa" class="block text-sm font-semibold text-gray-900 mb-2">No. WA aktif</label>
                            <input type="text" name="no_wa" id="no_wa" maxlength="20" inputmode="tel"
                                value="{{ old('no_wa', $tracer->no_wa ?? auth()->user()->phone ?? '') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_wa') border-red-500 @enderror">
                            @error('no_wa')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 mb-6 text-sm text-blue-800">
                        Data hanya dipakai untuk pendataan BKK & laporan penyaluran kerja. Bisa diperbarui kapan saja saat status berubah.
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                            Simpan Tracer Study
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-colors">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
