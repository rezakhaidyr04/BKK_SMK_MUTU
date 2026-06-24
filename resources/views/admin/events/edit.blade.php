<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Edit Acara</h1>
                        <p class="text-purple-100">Perbarui informasi acara karir.</p>
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
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 max-w-3xl mx-auto">
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

            <div class="max-w-3xl mx-auto space-y-6">
                <!-- Form Edit -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Edit Informasi Acara</h3>
                                <p class="text-sm text-gray-600">Perbarui detail acara di bawah ini</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Acara <span class="text-red-500">*</span></label>
                            <select name="type" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @foreach(['job_fair' => 'Job Fair', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'pelatihan' => 'Pelatihan', 'lainnya' => 'Lainnya'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type', $event->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="start_time" required
                                       value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Selesai <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <input type="datetime-local" name="end_time"
                                       value="{{ old('end_time', optional($event->end_time)?->format('Y-m-d\TH:i')) }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="5" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none">{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Poster Acara</label>
                            @if($event->poster)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster saat ini"
                                     class="w-40 h-28 object-cover rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-400 mt-1">Upload gambar baru untuk mengganti poster.</p>
                            </div>
                            @endif
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-400 transition">
                                <input type="file" name="poster" id="posterInput" accept="image/jpeg,image/png,image/webp"
                                       class="block mx-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-medium cursor-pointer">
                                <p class="text-xs text-gray-400 mt-2">JPG, PNG, WebP · maks. 3MB</p>
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
                                Perbarui Acara
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-100">
                    <div class="px-6 py-5 border-b border-red-100 bg-red-50">
                        <h3 class="font-bold text-red-700">Zona Berbahaya</h3>
                        <p class="text-sm text-red-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">Hapus Acara</p>
                            <p class="text-sm text-gray-500 mt-0.5">Acara akan dihapus secara permanen dari sistem.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                              onsubmit="return confirm('Yakin ingin menghapus acara ini? Tindakan tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Acara
                            </button>
                        </form>
                    </div>
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
