<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Perusahaan" subtitle="{{ $company->name }}">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.companies.show', $company) }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    @if($errors->any())
    <x-ui.alert type="danger" class="mb-6 max-w-3xl mx-auto">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('admin.companies.update', $company) }}"
          enctype="multipart/form-data"
          class="max-w-3xl mx-auto space-y-6">
                @csrf
                @method('PUT')

                {{-- ── Informasi Perusahaan ──────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-violet-50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white text-lg font-bold">
                                {{ substr($company->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Informasi Perusahaan</h3>
                                <p class="text-xs text-gray-500">Edit informasi dasar perusahaan</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label for="industry" class="block text-sm font-semibold text-gray-700 mb-2">Industri</label>
                            <input type="text" id="industry" name="industry" value="{{ old('industry', $company->industry) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                            <input type="url" id="website" name="website" value="{{ old('website', $company->website) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Perusahaan</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $company->email) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none">{{ old('description', $company->description) }}</textarea>
                        </div>

                        {{-- Verifikasi manual --}}
                        <div class="md:col-span-2 flex items-center gap-2 py-2">
                            <input type="checkbox" id="is_verified" name="is_verified" value="1"
                                   {{ old('is_verified', $company->is_verified) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_verified" class="text-sm text-gray-700">Tandai sebagai Terverifikasi</label>
                        </div>
                    </div>
                </div>

                {{-- ── Surat MoU ─────────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-green-50">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Surat MoU</h3>
                                <p class="text-xs text-gray-500">Upload file baru untuk menggantikan MoU yang lama</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Current MoU Status --}}
                        @if($company->mou_path)
                        <div class="md:col-span-2 flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">File MoU sudah ada</p>
                                    <p class="text-xs text-green-600">Upload file baru di bawah untuk menggantinya</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.companies.mou.download', $company) }}"
                               class="flex-shrink-0 text-xs text-green-700 font-semibold border border-green-300 px-3 py-1.5 rounded-lg hover:bg-green-100 transition">
                                Download MoU
                            </a>
                        </div>
                        @endif

                        {{-- Upload MoU --}}
                        <div class="md:col-span-2">
                            <label for="mou_path" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $company->mou_path ? 'Ganti File MoU' : 'Upload File MoU' }}
                            </label>
                            <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 hover:border-green-400 transition cursor-pointer"
                                 onclick="document.getElementById('mou_path').click()">
                                <div class="text-center">
                                    <svg class="mx-auto w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Klik untuk upload — PDF, JPG, PNG (maks. 10 MB)</p>
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

                        <div>
                            <label for="mou_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor MoU</label>
                            <input type="text" id="mou_number" name="mou_number" value="{{ old('mou_number', $company->mou_number) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>
                        <div>
                            <label for="mou_signed_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Ditandatangani</label>
                            <input type="date" id="mou_signed_at" name="mou_signed_at"
                                   value="{{ old('mou_signed_at', optional($company->mou_signed_at)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>
                        <div>
                            <label for="mou_expires_at" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir</label>
                            <input type="date" id="mou_expires_at" name="mou_expires_at"
                                   value="{{ old('mou_expires_at', optional($company->mou_expires_at)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.companies.show', $company) }}"
                       class="px-6 py-3 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
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
