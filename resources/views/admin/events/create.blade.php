<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Buat Acara Baru" subtitle="Jadwalkan acara karir untuk siswa dan alumni.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf

            <div>
                <label class="ui-label">Nama Acara <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="ui-input" placeholder="Contoh: Job Fair SMK MUTU 2025">
            </div>

            <div>
                <label class="ui-label">Tipe Acara <span class="text-red-500">*</span></label>
                <select name="type" required class="ui-select">
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
                    <label class="ui-label">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Waktu Selesai <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="ui-input">
                </div>
            </div>

            <div>
                <label class="ui-label">Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}" required class="ui-input" placeholder="Contoh: Aula SMK MUTU / Online via Zoom">
            </div>

            <div>
                <label class="ui-label">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required class="ui-textarea" placeholder="Deskripsi lengkap acara...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="ui-label">Poster Acara <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition" id="posterDropZone">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-slate-500 mb-2">Klik atau drag & drop gambar di sini</p>
                    <p class="text-xs text-slate-400">JPG, PNG, WebP · maks. 3MB</p>
                    <input type="file" name="poster" id="posterInput" accept="image/jpeg,image/png,image/webp" class="mt-3 block mx-auto text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium cursor-pointer">
                </div>
                <img id="posterPreview" src="" alt="Preview" class="hidden mt-3 w-40 h-28 object-cover rounded-xl border border-slate-200">
            </div>

            <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                <x-ui.btn type="submit">Simpan Acara</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>

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
