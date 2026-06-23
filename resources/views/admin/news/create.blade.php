<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Tulis Berita Baru</h2>
            <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-600 hover:text-gray-900">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow sm:rounded-xl p-6">
                <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Berita</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Judul berita yang menarik...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Contoh: Tips Karir, Info Lowongan, Inspirasi...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP · maks. 2MB</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Isi Berita</label>
                        <textarea name="content" rows="12" required
                                  class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Tulis isi berita di sini...">{{ old('content') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <input type="checkbox" id="is_published" name="is_published" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published" class="text-sm font-medium text-gray-700">Publish sekarang</label>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 transition">
                            Simpan Berita
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 border border-gray-300 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
