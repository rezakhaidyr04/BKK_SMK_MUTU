<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Acara</h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow sm:rounded-xl p-6">
                <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Acara</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Acara</label>
                        <select name="type" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['job_fair' => 'Job Fair', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'pelatihan' => 'Pelatihan', 'lainnya' => 'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $event->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" required
                                   value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Selesai</label>
                            <input type="datetime-local" name="end_time"
                                   value="{{ old('end_time', optional($event->end_time)?->format('Y-m-d\TH:i')) }}"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="5" required
                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Poster Acara</label>
                        @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" class="w-40 h-28 object-cover rounded-lg border border-gray-200 mb-2">
                        <p class="text-xs text-gray-400 mb-2">Upload baru untuk mengganti poster.</p>
                        @endif
                        <input type="file" name="poster" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition">
                            Perbarui Acara
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 border border-gray-300 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50">Batal</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="ml-auto" onsubmit="return confirm('Hapus acara ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition">Hapus</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
