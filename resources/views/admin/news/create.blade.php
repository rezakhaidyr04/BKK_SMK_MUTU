<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Tulis Berita Baru</h1>
                        <p class="text-purple-100">Publikasikan artikel berita karir untuk siswa & alumni.</p>
                    </div>
                    <a href="{{ route('admin.news.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" id="newsForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Konten Utama -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">Konten Berita</h3>
                                        <p class="text-sm text-gray-600">Tulis judul dan isi berita</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                           placeholder="Tulis judul berita yang menarik...">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Isi Berita <span class="text-red-500">*</span></label>
                                    <!-- Toolbar Editor -->
                                    <div class="border border-gray-200 rounded-t-xl bg-gray-50 px-3 py-2 flex flex-wrap gap-1 items-center">
                                        <button type="button" onclick="execCmd('bold')" title="Bold"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 font-bold text-sm w-8 h-8">B</button>
                                        <button type="button" onclick="execCmd('italic')" title="Italic"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 italic text-sm w-8 h-8">I</button>
                                        <button type="button" onclick="execCmd('underline')" title="Underline"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-700 underline text-sm w-8 h-8">U</button>
                                        <div class="w-px bg-gray-300 mx-1 h-5"></div>
                                        <button type="button" onclick="execCmd('insertUnorderedList')" title="Bullet List"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-600 w-8 h-8">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="execCmd('insertOrderedList')" title="Numbered List"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-600 w-8 h-8">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7m0 0H5m2 0v2m0-2V3M9 19H7m0 0H5m2 0v2m0-2v-2M9 12H5m14-7h-4m4 7h-4m4 7h-4"/>
                                            </svg>
                                        </button>
                                        <div class="w-px bg-gray-300 mx-1 h-5"></div>
                                        <button type="button" onclick="execCmd('justifyLeft')" title="Rata Kiri"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-600 w-8 h-8 text-xs">≡</button>
                                        <button type="button" onclick="execCmd('justifyCenter')" title="Rata Tengah"
                                                class="p-1.5 rounded hover:bg-gray-200 transition text-gray-600 w-8 h-8 text-xs">☰</button>
                                        <div class="w-px bg-gray-300 mx-1 h-5"></div>
                                        <select onchange="execCmdValue('formatBlock', this.value); this.value=''"
                                                class="px-2 py-1 text-xs border border-gray-200 rounded text-gray-600 bg-white h-8">
                                            <option value="">Format</option>
                                            <option value="h2">Judul 2</option>
                                            <option value="h3">Judul 3</option>
                                            <option value="p">Paragraf</option>
                                        </select>
                                        <div class="w-px bg-gray-300 mx-1 h-5"></div>
                                        <!-- Insert Image Button -->
                                        <button type="button" onclick="document.getElementById('inlineImageInput').click()" title="Sisipkan Gambar"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition text-gray-600 text-xs font-medium h-8">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Gambar
                                        </button>
                                        <input type="file" id="inlineImageInput" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">
                                        <!-- Upload progress indicator -->
                                        <span id="uploadStatus" class="text-xs text-indigo-600 hidden">Mengupload...</span>
                                    </div>
                                    <div id="editor"
                                         contenteditable="true"
                                         class="min-h-64 w-full px-4 py-3 border border-gray-200 border-t-0 rounded-b-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition prose max-w-none"
                                         style="min-height: 300px;">
                                        {!! old('content') !!}
                                    </div>
                                    <textarea name="content" id="contentInput" class="hidden" required>{{ old('content') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Kanan -->
                    <div class="space-y-6">
                        <!-- Publikasi -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100">
                                <h3 class="font-bold text-gray-900">Publikasi</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Status</p>
                                        <p class="text-xs text-gray-500" id="statusText">Draft</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="is_published" name="is_published" value="1"
                                               class="sr-only peer" {{ old('is_published') ? 'checked' : '' }}
                                               onchange="document.getElementById('statusText').textContent = this.checked ? 'Dipublikasikan' : 'Draft'">
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Berita
                                </button>
                                <a href="{{ route('admin.news.index') }}"
                                   class="w-full inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                                    Batal
                                </a>
                            </div>
                        </div>

                        <!-- Meta -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100">
                                <h3 class="font-bold text-gray-900">Detail Berita</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                                    <input type="text" name="category" value="{{ old('category') }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                           placeholder="Contoh: Tips Karir, Info Lowongan...">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail</label>
                                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-indigo-400 transition">
                                        <img id="thumbPreview" src="" alt="" class="hidden w-full h-32 object-cover rounded-lg mb-2">
                                        <svg id="thumbIcon" class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <input type="file" name="thumbnail" id="thumbInput" accept="image/jpeg,image/png,image/webp"
                                               class="block mx-auto text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs cursor-pointer">
                                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP · maks. 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@push('scripts')
<script>
const uploadUrl = "{{ route('admin.news.upload-image') }}";
const csrfToken = "{{ csrf_token() }}";

function execCmd(cmd) {
    document.getElementById('editor').focus();
    document.execCommand(cmd, false, null);
}
function execCmdValue(cmd, val) {
    if (!val) return;
    document.getElementById('editor').focus();
    document.execCommand(cmd, false, val);
}

// Simpan posisi kursor sebelum klik toolbar
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

// Upload gambar inline ke server
document.getElementById('inlineImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const status = document.getElementById('uploadStatus');
    status.classList.remove('hidden');

    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', csrfToken);

    fetch(uploadUrl, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.url) {
            restoreSelection();
            document.getElementById('editor').focus();
            document.execCommand('insertHTML', false,
                `<img src="${data.url}" alt="Gambar" style="max-width:100%;height:auto;border-radius:8px;margin:8px 0;" />`
            );
        }
        status.classList.add('hidden');
        e.target.value = '';
    })
    .catch(() => {
        status.textContent = 'Gagal upload!';
        setTimeout(() => {
            status.classList.add('hidden');
            status.textContent = 'Mengupload...';
        }, 2000);
        e.target.value = '';
    });
});

// Sync editor ke textarea sebelum submit
document.getElementById('newsForm').addEventListener('submit', function() {
    document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
});

// Preview thumbnail
document.getElementById('thumbInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('thumbPreview');
            const icon = document.getElementById('thumbIcon');
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
</x-app-layout>