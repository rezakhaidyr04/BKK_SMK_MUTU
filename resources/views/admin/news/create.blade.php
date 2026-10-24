<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Buat Berita Baru" subtitle="Tulis artikel karir, tips dunia kerja, atau informasi industri untuk pencari kerja dan alumni." eyebrow="Admin › Berita">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tulis Berita Baru
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <x-ui.form-errors />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="ui-form-stack">
            @csrf

            <div>
                <label class="ui-label">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="ui-input" placeholder="Contoh: 5 Tips Menulis CV yang Menarik Perhatian Rekruiter">
            </div>

            <div>
                <label class="ui-label">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required class="ui-select">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="career_tips"  {{ old('category') === 'career_tips'  ? 'selected' : '' }}>Tips Karir</option>
                    <option value="work_world"   {{ old('category') === 'work_world'   ? 'selected' : '' }}>Dunia Kerja</option>
                    <option value="industry_info" {{ old('category') === 'industry_info' ? 'selected' : '' }}>Informasi Industri</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="ui-label">Status Publish <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" name="is_published" value="1" id="isPublishedToggle" class="sr-only peer" {{ old('is_published') ? 'checked' : 'checked' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                    <p class="text-xs text-slate-500 mt-1">Nonaktifkan jika ingin simpan sebagai draft.</p>
                </div>
                <div id="publishFields" class="space-y-2">
                    <div>
                        <label class="ui-label">Tanggal Terbit <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="ui-input" placeholder="Tanggal penerbitan">
                        <p class="text-xs text-slate-400 mt-1">Kosongkan untuk publish segera saat disimpan.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="ui-label">Isi Berita <span class="text-red-500">*</span></label>
                <textarea name="content" rows="10" required class="ui-textarea" placeholder="Tulis isi lengkap berita di sini...">{{ old('content') }}</textarea>
                <p class="text-xs text-slate-400 mt-1">HTML terbatas diizinkan (paragraf, bold, italic, link, gambar, dll).</p>
            </div>

            <div>
                <label class="ui-label">Thumbnail Berita <span class="text-slate-400 font-normal ml-1">(opsional)</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition" id="thumbDropZone">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-slate-500 mb-2">Klik atau drag & drop gambar di sini</p>
                    <p class="text-xs text-slate-400">JPG, PNG, WebP · maks. 3MB</p>
                    <input type="file" name="thumbnail" id="thumbnailInput" accept="image/jpeg,image/png,image/webp" class="mt-3 block mx-auto text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium cursor-pointer">
                </div>
                <img id="thumbPreview" src="" alt="Preview" class="hidden mt-3 w-40 h-28 object-contain bg-slate-50 rounded-xl border border-slate-200">
            </div>

            <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                <x-ui.btn type="submit">Simpan Berita</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>

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
    </script>
    @endpush
        </div>
    </div>
</x-app-layout>
