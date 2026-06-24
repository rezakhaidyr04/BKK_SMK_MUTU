<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Edit Acara" subtitle="Perbarui informasi acara karir.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-medium">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div>
                <label class="ui-label">Nama Acara</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="ui-input">
            </div>
            <div>
                <label class="ui-label">Tipe Acara</label>
                <select name="type" required class="ui-select">
                    @foreach(['job_fair' => 'Job Fair', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'pelatihan' => 'Pelatihan', 'lainnya' => 'Lainnya'] as $val => $label)
                    <option value="{{ $val }}" {{ old('type', $event->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="ui-label">Waktu Mulai</label>
                    <input type="datetime-local" name="start_time" required value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Waktu Selesai</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', optional($event->end_time)?->format('Y-m-d\TH:i')) }}" class="ui-input">
                </div>
            </div>
            <div>
                <label class="ui-label">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="ui-input">
            </div>
            <div>
                <label class="ui-label">Deskripsi</label>
                <textarea name="description" rows="5" required class="ui-textarea">{{ old('description', $event->description) }}</textarea>
            </div>
            <div>
                <label class="ui-label">Poster Acara</label>
                @if($event->poster)
                <img src="{{ asset('storage/' . $event->poster) }}" alt="" class="w-40 h-28 object-cover rounded-lg border border-slate-200 mb-2">
                <p class="text-xs text-slate-400 mb-2">Upload baru untuk mengganti poster.</p>
                @endif
                <input type="file" name="poster" accept="image/jpeg,image/png,image/webp" class="ui-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium">
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Perbarui Acara</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>

    <x-ui.panel class="ui-form-medium mt-6">
        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus acara ini?')" class="flex items-center justify-between gap-4">
            @csrf @method('DELETE')
            <div>
                <p class="font-semibold text-red-700">Hapus Acara</p>
                <p class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <x-ui.btn variant="danger" type="submit" size="sm">Hapus Acara</x-ui.btn>
        </form>
    </x-ui.panel>
</x-app-layout>
