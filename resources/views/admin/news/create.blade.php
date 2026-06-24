<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Tulis Berita Baru" subtitle="Publikasikan artikel berita karir untuk platform.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf

            <div>
                <label class="ui-label">Judul Berita</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="ui-input" placeholder="Judul berita yang menarik...">
            </div>
            <div>
                <label class="ui-label">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" class="ui-input" placeholder="Contoh: Tips Karir, Info Lowongan...">
            </div>
            <div>
                <label class="ui-label">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="ui-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium">
                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP · maks. 2MB</p>
            </div>
            <div>
                <label class="ui-label">Isi Berita</label>
                <textarea name="content" rows="12" required class="ui-textarea" placeholder="Tulis isi berita di sini...">{{ old('content') }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_published" name="is_published" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('is_published') ? 'checked' : '' }}>
                <label for="is_published" class="text-sm text-slate-700">Publish sekarang</label>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Simpan Berita</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
