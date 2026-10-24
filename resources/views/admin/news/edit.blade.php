<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Edit Berita" subtitle="Perbarui informasi artikel karir." eyebrow="Admin › Berita">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Berita
                </span>
                <span class="page-banner__chip">{{ Str::limit($news->title, 24) }}</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <x-ui.form-errors />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-ui.panel class="ui-form-narrow mx-0">
                <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" class="ui-form-stack">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="ui-label">Judul Berita <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $news->title) }}" required class="ui-input">
                    </div>

                    <div>
                        <label class="ui-label">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" required class="ui-select">
                            @foreach(['career_tips' => 'Tips Karir', 'work_world' => 'Dunia Kerja', 'industry_info' => 'Informasi Industri'] as $val => $label)
                            <option value="{{ $val }}" {{ old('category', $news->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="ui-label">Status Publish <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="is_published" value="1" id="isPublishedToggle" class="sr-only peer" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <p class="text-xs text-slate-500 mt-1">Nonaktifkan jika ingin simpan sebagai draft.</p>
                        </div>
                        <div id="publishFields" class="{{ old('is_published', $news->is_published) ? '' : 'hidden' }} space-y-2">
                            <div>
                                <label class="ui-label">Tanggal Terbit <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                                <input type="datetime-local" name="published_at"
                                       value="{{ old('published_at', optional($news->published_at)?->format('Y-m-d\TH:i')) }}"
                                       class="ui-input">
                                <p class="text-xs text-slate-400 mt-1">Kosongkan untuk publish segera saat disimpan.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="ui-label">Isi Berita <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="10" required class="ui-textarea">{{ old('content', $news->content) }}</textarea>
                    </div>

                    <div>
                        <label class="ui-label">Thumbnail Berita</label>
                        @if($news->thumbnail)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="Thumbnail saat ini" class="w-40 h-28 object-contain bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-400 mt-1">Upload gambar baru untuk mengganti thumbnail.</p>
                        </div>
                        @endif
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition">
                            <input type="file" name="thumbnail" id="thumbnailInput" accept="image/jpeg,image/png,image/webp"
                                   class="block mx-auto text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium cursor-pointer">
                            <p class="text-xs text-slate-400 mt-2">JPG, PNG, WebP · maks. 3MB</p>
                        </div>
                        <img id="thumbPreview" src="" alt="Preview" class="hidden mt-3 w-40 h-28 object-contain bg-slate-50 rounded-xl border border-slate-200">
                    </div>

                    <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                        <x-ui.btn type="submit">Perbarui Berita</x-ui.btn>
                        <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Batal</x-ui.btn>
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
                    <p class="font-semibold text-slate-900 mb-1">Hapus Berita</p>
                    <p class="text-sm text-slate-500 mb-4">Berita akan dihapus secara permanen dari sistem.</p>
                    <form method="POST" action="{{ route('admin.news.destroy', $news) }}" data-confirm="Yakin ingin menghapus berita ini? Tindakan tidak dapat dibatalkan." data-confirm-title="Hapus" data-confirm-ok="Hapus" data-confirm-variant="danger">
                        @csrf @method('DELETE')
                        <x-ui.btn variant="danger" type="submit" class="w-full justify-center">Hapus Berita</x-ui.btn>
                    </form>
                </div>
            </x-ui.panel>
        </div>
    </div>

    @push('scripts')
    <script>
    document.getElementById('thumbnailInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('thumbPreview');
                preview.src = ev.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    const isPublishedToggle = document.getElementById('isPublishedToggle');
    const publishFields = document.getElementById('publishFields');
    if (isPublishedToggle && publishFields) {
        isPublishedToggle.addEventListener('change', function() {
            publishFields.classList.toggle('hidden', !this.checked);
        });
    }
    </script>
    @endpush
        </div>
    </div>
</x-app-layout>
