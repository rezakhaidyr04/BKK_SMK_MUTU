<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Edit Berita" subtitle="Perbarui artikel berita karir.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors />

    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data" id="newsForm" class="ui-form-stack">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Konten Utama -->
            <div class="lg:col-span-2">
                <x-ui.panel class="mx-0 h-full">
                    <div class="space-y-6">
                        <div>
                            <label class="ui-label">Judul Berita <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $news->title) }}" required class="ui-input">
                        </div>
                        <div>
                            <label class="ui-label">Isi Berita <span class="text-red-500">*</span></label>
                            <!-- Toolbar Editor -->
                            <div class="border border-slate-200 rounded-t-xl bg-slate-50 px-3 py-2 flex flex-wrap gap-1 items-center">
                                <button type="button" onclick="execCmd('bold')" title="Bold" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 font-bold text-sm w-8 h-8">B</button>
                                <button type="button" onclick="execCmd('italic')" title="Italic" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 italic text-sm w-8 h-8">I</button>
                                <button type="button" onclick="execCmd('underline')" title="Underline" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-700 underline text-sm w-8 h-8">U</button>
                                <div class="w-px bg-slate-300 mx-1 h-5"></div>
                                <button type="button" onclick="execCmd('insertUnorderedList')" title="Bullet List" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-600 w-8 h-8">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                </button>
                                <button type="button" onclick="execCmd('insertOrderedList')" title="Numbered List" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-600 w-8 h-8">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7m0 0H5m2 0v2m0-2V3M9 19H7m0 0H5m2 0v2m0-2v-2M9 12H5m14-7h-4m4 7h-4m4 7h-4"/></svg>
                                </button>
                                <div class="w-px bg-slate-300 mx-1 h-5"></div>
                                <button type="button" onclick="execCmd('justifyLeft')" title="Rata Kiri" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-600 w-8 h-8 text-xs">≡</button>
                                <button type="button" onclick="execCmd('justifyCenter')" title="Rata Tengah" class="p-1.5 rounded hover:bg-slate-200 transition text-slate-600 w-8 h-8 text-xs">☰</button>
                                <div class="w-px bg-slate-300 mx-1 h-5"></div>
                                <select onchange="execCmdValue('formatBlock', this.value); this.value=''" class="px-2 py-1 text-xs border border-slate-200 rounded text-slate-600 bg-white h-8">
                                    <option value="">Format</option>
                                    <option value="h2">Judul 2</option>
                                    <option value="h3">Judul 3</option>
                                    <option value="p">Paragraf</option>
                                </select>
                                <div class="w-px bg-slate-300 mx-1 h-5"></div>
                                <button type="button" onclick="document.getElementById('inlineImageInput').click()" title="Sisipkan Gambar" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition text-slate-600 text-xs font-medium h-8">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Gambar
                                </button>
                                <input type="file" id="inlineImageInput" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">
                                <span id="uploadStatus" class="text-xs text-blue-600 hidden">Mengupload...</span>
                            </div>
                            <div id="editor" contenteditable="true" class="min-h-[300px] w-full px-4 py-3 border border-slate-200 border-t-0 rounded-b-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition prose max-w-none">
                                {!! old('content', $news->content) !!}
                            </div>
                            <textarea name="content" id="contentInput" class="hidden" required>{{ old('content', $news->content) }}</textarea>
                        </div>
                    </div>
                </x-ui.panel>
            </div>

            <!-- Sidebar Kanan -->
            <div class="space-y-6">
                <!-- Publikasi -->
                <x-ui.panel title="Publikasi" class="mx-0">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Status</p>
                                <p class="text-xs text-slate-500" id="statusText">{{ $news->is_published ? 'Dipublikasikan' : 'Draft' }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_published" name="is_published" value="1" class="sr-only peer" {{ $news->is_published ? 'checked' : '' }} onchange="document.getElementById('statusText').textContent = this.checked ? 'Dipublikasikan' : 'Draft'">
                                <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                        <x-ui.btn type="submit" class="w-full justify-center">Perbarui Berita</x-ui.btn>
                        <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}" class="w-full justify-center">Batal</x-ui.btn>
                    </div>
                </x-ui.panel>

                <!-- Meta -->
                <x-ui.panel title="Detail Berita" class="mx-0">
                    <div class="space-y-4">
                        <div>
                            <label class="ui-label">Kategori</label>
                            <input type="text" name="category" value="{{ old('category', $news->category) }}" class="ui-input" placeholder="Contoh: Tips Karir...">
                        </div>
                        <div>
                            <label class="ui-label">Thumbnail</label>
                            @if($news->thumbnail)
                            <img id="thumbPreview" src="{{ asset('storage/' . $news->thumbnail) }}" alt="Thumbnail saat ini" class="w-full h-32 object-cover rounded-xl mb-2 border border-slate-200">
                            @else
                            <img id="thumbPreview" src="" alt="" class="hidden w-full h-32 object-cover rounded-xl mb-2 border border-slate-200">
                            @endif
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-3 text-center hover:border-blue-400 transition">
                                <input type="file" name="thumbnail" id="thumbInput" accept="image/jpeg,image/png,image/webp" class="block mx-auto text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-xs cursor-pointer">
                                <p class="text-xs text-slate-400 mt-1">Upload baru untuk mengganti · maks. 2MB</p>
                            </div>
                        </div>
                    </div>
                </x-ui.panel>
            </div>
        </div>
    </form>
    
    <div class="mt-6 lg:w-2/3">
        <x-ui.panel>
            <div class="px-6 py-5 border-b border-red-100 bg-red-50 -mx-6 -mt-6 mb-6 rounded-t-xl">
                <h3 class="font-bold text-red-700">Zona Berbahaya</h3>
            </div>
            <div>
                <p class="font-semibold text-slate-900 mb-1">Hapus Berita</p>
                <p class="text-sm text-slate-500 mb-4">Berita akan dihapus secara permanen.</p>
                <form method="POST" action="{{ route('admin.news.destroy', $news) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                    @csrf @method('DELETE')
                    <x-ui.btn variant="danger" type="submit">Hapus Berita</x-ui.btn>
                </form>
            </div>
        </x-ui.panel>
    </div>

    @push('scripts')
    <script>
    const uploadUrl = "{{ route('admin.news.upload-image') }}";
    const csrfToken = "{{ csrf_token() }}";
    
    function execCmd(cmd) { document.getElementById('editor').focus(); document.execCommand(cmd, false, null); }
    function execCmdValue(cmd, val) { if (!val) return; document.getElementById('editor').focus(); document.execCommand(cmd, false, val); }
    
    let savedRange = null;
    document.getElementById('editor').addEventListener('keyup mouseup', function() {
        const sel = window.getSelection();
        if (sel.rangeCount) savedRange = sel.getRangeAt(0).cloneRange();
    });
    
    function restoreSelection() {
        if (savedRange) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedRange);
        }
    }
    
    document.getElementById('inlineImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const status = document.getElementById('uploadStatus');
        status.classList.remove('hidden');
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', csrfToken);
        fetch(uploadUrl, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.url) {
                restoreSelection();
                document.getElementById('editor').focus();
                document.execCommand('insertHTML', false, `<img src="${data.url}" alt="Gambar" class="inline-rich-img" />`);
            }
            status.classList.add('hidden');
            e.target.value = '';
        })
        .catch(() => {
            status.textContent = 'Gagal upload!';
            setTimeout(() => { status.classList.add('hidden'); status.textContent = 'Mengupload...'; }, 2000);
            e.target.value = '';
        });
    });
    
    document.getElementById('newsForm').addEventListener('submit', function() {
        document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
    });
    
    document.getElementById('thumbInput').addEventListener('change', function(e) {
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
</x-app-layout>
