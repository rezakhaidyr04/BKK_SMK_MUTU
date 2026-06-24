<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Buat Acara Baru" subtitle="Jadwalkan acara karir untuk siswa dan alumni.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-medium">
        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf

            <div>
                <label class="ui-label">Nama Acara</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="ui-input" placeholder="Nama acara...">
            </div>
            <div>
                <label class="ui-label">Tipe Acara</label>
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
                    <label class="ui-label">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Waktu Selesai <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="ui-input">
                </div>
            </div>
            <div>
                <label class="ui-label">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" required class="ui-input" placeholder="Contoh: Aula SMK MUTU / Online via Zoom">
            </div>
            <div>
                <label class="ui-label">Deskripsi</label>
                <textarea name="description" rows="5" required class="ui-textarea" placeholder="Deskripsi acara...">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="ui-label">Poster Acara <span class="text-slate-400 font-normal">(opsional)</span></label>
                <input type="file" name="poster" accept="image/jpeg,image/png,image/webp" class="ui-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium">
                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP · maks. 3MB</p>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Simpan Acara</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
