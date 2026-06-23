<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Buat Acara Baru</h2>
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
                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Acara</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Nama acara...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Acara</label>
                        <select name="type" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="job_fair"  {{ old('type') === 'job_fair'  ? 'selected' : '' }}>Job Fair</option>
                            <option value="seminar"   {{ old('type') === 'seminar'   ? 'selected' : '' }}>Seminar</option>
                            <option value="workshop"  {{ old('type') === 'workshop'  ? 'selected' : '' }}>Workshop</option>
                            <option value="pelatihan" {{ old('type') === 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="lainnya"   {{ old('type') === 'lainnya'   ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Selesai <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Contoh: Aula SMK MUTU / Online via Zoom">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" rows="5" required
                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Deskripsi acara...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Poster Acara <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="file" name="poster" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP · maks. 3MB</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition">
                            Simpan Acara
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 border border-gray-300 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
