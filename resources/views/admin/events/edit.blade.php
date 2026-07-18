<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Edit Acara" subtitle="Perbarui informasi acara karir.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-ui.panel class="ui-form-narrow mx-0">
                <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="ui-form-stack">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="ui-label">Nama Acara <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="ui-input">
                    </div>

                    <div>
                        <label class="ui-label">Tipe Acara <span class="text-red-500">*</span></label>
                        <select name="type" required class="ui-select">
                            @foreach(['job_fair' => 'Job Fair', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'pelatihan' => 'Pelatihan', 'lainnya' => 'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $event->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="ui-label">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_time" required
                                   value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}"
                                   class="ui-input">
                        </div>
                        <div>
                            <label class="ui-label">Waktu Selesai <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                            <input type="datetime-local" name="end_time"
                                   value="{{ old('end_time', optional($event->end_time)?->format('Y-m-d\TH:i')) }}"
                                   class="ui-input">
                        </div>
                    </div>

                    <div>
                        <label class="ui-label">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="ui-input">
                    </div>

                    <div>
                        <label class="ui-label">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required class="ui-textarea">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div>
                        <label class="ui-label">Poster Acara</label>
                        @if($event->poster)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster saat ini" class="w-40 h-28 object-cover rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-400 mt-1">Upload gambar baru untuk mengganti poster.</p>
                        </div>
                        @endif
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition">
                            <input type="file" name="poster" id="posterInput" accept="image/jpeg,image/png,image/webp"
                                   class="block mx-auto text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium cursor-pointer">
                            <p class="text-xs text-slate-400 mt-2">JPG, PNG, WebP · maks. 3MB</p>
                        </div>
                        <img id="posterPreview" src="" alt="Preview" class="hidden mt-3 w-40 h-28 object-cover rounded-xl border border-slate-200">
                    </div>

                    <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                        <x-ui.btn type="submit">Perbarui Acara</x-ui.btn>
                        <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}">Batal</x-ui.btn>
                    </div>
                </form>
            </x-ui.panel>
        </div>

        <div>
            <x-ui.panel>
                <div class="px-6 py-5 border-b border-red-100 bg-red-50 -mx-6 -mt-6 mb-6 rounded-t-xl">
                    <h3 class="font-bold text-red-700">Zona Berbahaya</h3>
                    <p class="text-sm text-red-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 mb-1">Hapus Acara</p>
                    <p class="text-sm text-slate-500 mb-4">Acara akan dihapus secara permanen dari sistem beserta data pesertanya.</p>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Yakin ingin menghapus acara ini? Tindakan tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <x-ui.btn variant="danger" type="submit" class="w-full justify-center">Hapus Acara</x-ui.btn>
                    </form>
                </div>
            </x-ui.panel>
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
