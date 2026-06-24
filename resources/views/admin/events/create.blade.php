<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Buat Acara Baru</h1>
                        <p class="text-purple-100">Jadwalkan acara karir untuk siswa dan alumni.</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Informasi Acara</h3>
                                <p class="text-sm text-gray-600">Lengkapi detail acara yang akan dibuat</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                   placeholder="Contoh: Job Fair SMK MUTU 2025">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Acara <span class="text-red-500">*</span></label>
                            <select name="type" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="job_fair"  {{ old('type') === 'job_fair'  ? 'selected' : '' }}>Job Fair</option>
                                <option value="seminar"   {{ old('type') === 'seminar'   ? 'selected' : '' }}>Seminar</option>
                                <option value="workshop"  {{ old('type') === 'workshop'  ? 'selected' : '' }}>Workshop</option>
                                <option value="pelatihan" {{ old('type') === 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="lainnya"   {{ old('type') === 'lainnya'   ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Selesai <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="location" value="{{ old('location') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                   placeholder="Contoh: Aula SMK MUTU / Online via Zoom">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                                      placeholder="Deskripsi lengkap acara...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Acara <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-400 transition" id="posterDropZone">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-gray-500 mb-2">Klik atau drag & drop gambar di sini</p>
                                <p class="text-xs text-gray-400">JPG, PNG, WebP · maks. 3MB</p>
                                <input type="file" name="poster" id="posterInput" accept="image/jpeg,image/png,image/webp"
                                       class="mt-3 block mx-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-medium cursor-pointer">
                            </div>
                            <img id="posterPreview" src="" alt="Preview" class="hidden mt-3 w-40 h-28 object-cover rounded-xl border border-gray-200">
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.events.index') }}"
                               class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Acara
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
document.getElementById('posterInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('posterPreview');
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
</x-app-layout>
