<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Tambah Perusahaan" subtitle="Tambahkan data perusahaan mitra baru. Akun login dibuat setelah perusahaan disetujui.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.companies.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    @if($errors->any())
    <x-ui.alert type="danger" class="mb-6 max-w-3xl mx-auto">
        <div class="flex items-start gap-3">
            <ul class="text-sm space-y-1 list-disc pl-2">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data"
          class="max-w-3xl mx-auto space-y-6">
                @csrf

                {{-- ── Informasi Perusahaan ──────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-violet-50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Informasi Perusahaan</h3>
                                <p class="text-xs text-gray-500">Data dasar perusahaan mitra</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="PT. Contoh Indonesia"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}">
                        </div>

                        {{-- Industri --}}
                        <div>
                            <label for="industry" class="block text-sm font-semibold text-gray-700 mb-2">Industri</label>
                            <input type="text" id="industry" name="industry" value="{{ old('industry') }}"
                                   placeholder="Manufaktur, Teknologi, dll."
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        {{-- Website --}}
                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}"
                                   placeholder="https://contoh.com"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Perusahaan</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   placeholder="hrd@contoh.com"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <p class="text-xs text-gray-400 mt-1">Email ini akan digunakan sebagai login akun perusahaan.</p>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   placeholder="021-12345678"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}"
                                   placeholder="Jl. Contoh No. 1, Kota"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>


                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Perusahaan</label>
                            <textarea id="description" name="description" rows="4"
                                      placeholder="Ceritakan tentang perusahaan ini..."
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── Surat MoU ──────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-green-50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Surat MoU / Perjanjian Kerjasama</h3>
                                <p class="text-xs text-gray-500">File MoU disimpan secara privat — hanya dapat diunduh oleh admin</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Upload MoU --}}
                        <div class="md:col-span-2">
                            <label for="mou_path" class="block text-sm font-semibold text-gray-700 mb-2">File MoU</label>
                            <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-6 hover:border-green-400 transition cursor-pointer"
                                 onclick="document.getElementById('mou_path').click()">
                                <div class="text-center">
                                    <svg class="mx-auto w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 mb-1">Klik untuk upload atau drag & drop</p>
                                    <p class="text-xs text-gray-400">PDF, JPG, PNG — maks. 10 MB</p>
                                    <p id="mou_file_name" class="mt-2 text-sm font-medium text-green-600 hidden"></p>
                                </div>
                                <input type="file" id="mou_path" name="mou_path"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       class="absolute inset-0 opacity-0 cursor-pointer"
                                       onchange="showFileName(this, 'mou_file_name')">
                            </div>
                            @error('mou_path')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor MoU --}}
                        <div>
                            <label for="mou_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor MoU</label>
                            <input type="text" id="mou_number" name="mou_number" value="{{ old('mou_number') }}"
                                   placeholder="MOU/BKK/2024/001"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>

                        {{-- Tanggal Berlaku --}}
                        <div>
                            <label for="mou_signed_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Ditandatangani</label>
                            <input type="date" id="mou_signed_at" name="mou_signed_at" value="{{ old('mou_signed_at') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>

                        {{-- Tanggal Berakhir --}}
                        <div>
                            <label for="mou_expires_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir</label>
                            <input type="date" id="mou_expires_at" name="mou_expires_at" value="{{ old('mou_expires_at') }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>
                    </div>
                </div>

                {{-- Info: Akun dibuat setelah approval --}}
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-blue-800">Tentang Pembuatan Akun</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Data perusahaan akan tersimpan dengan status <strong>Menunggu</strong>.
                            Akun login hanya dapat dibuat setelah perusahaan <strong>disetujui (approved)</strong> oleh admin.
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.companies.index') }}"
                       class="px-6 py-3 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Perusahaan
                    </button>
                </div>
            </form>

    @push('scripts')
    <script>
    function showFileName(input, targetId) {
        const el = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            el.textContent = '✓ ' + input.files[0].name;
            el.classList.remove('hidden');
        }
    }
    </script>
    @endpush
</x-app-layout>
