<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Sertifikat Saya" subtitle="Tunjukkan pencapaian dan kualifikasi Anda." eyebrow="Dashboard › Sertifikat">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ $certificates->count() }} Sertifikat · Portofolio
                </span>
                <span class="page-banner__chip">Pencari Kerja</span>
            </x-slot:chips>
            <x-slot:actions>
                <button
                    type="button"
                    onclick="openUploadModal()"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Unggah Sertifikat
                </button>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            @if($certificates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($certificates as $cert)
                <div class="ui-panel overflow-hidden">
                    <div class="h-36 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-14 h-14 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="ui-panel-body">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h3 class="font-bold text-slate-900">{{ $cert->title }}</h3>
                            @php $ext = strtoupper(pathinfo($cert->file_path, PATHINFO_EXTENSION)); @endphp
                            @if($ext)
                                <span class="shrink-0 rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-bold text-blue-700">{{ $ext }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 mb-1">{{ $cert->issuer }}</p>
                        <p class="text-xs text-slate-400 mb-4">{{ $cert->issue_date->format('M Y') }}</p>
                        <div class="flex flex-wrap items-center gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('certificates.download', $cert->id) }}?preview=1" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat
                            </a>
                            <a href="{{ route('certificates.download', $cert->id) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh
                            </a>
                            <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST" class="ml-auto" onsubmit="return confirm('Hapus sertifikat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 hover:text-red-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    icon="document"
                    title="Belum ada sertifikat"
                    description="Unggah sertifikat pertama Anda untuk memulai dan tunjukkan pencapaian Anda."
                >
                    <x-slot:action>
                        <button
                            type="button"
                            onclick="openUploadModal()"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Unggah Sertifikat Pertama
                        </button>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true" aria-label="Unggah Sertifikat">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeUploadModal()" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-start sm:items-center justify-center p-4 py-8">
            <div class="relative bg-white rounded-2xl w-full max-w-md shadow-2xl max-h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-slate-900">Unggah Sertifikat</h3>
                        <button type="button" onclick="closeUploadModal()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition" aria-label="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="ui-label">Judul</label>
                            <input type="text" name="title" required class="ui-input" placeholder="Contoh: Sertifikat Web Development">
                        </div>
                        <div>
                            <label class="ui-label">Penerbit</label>
                            <input type="text" name="issuer" required class="ui-input" placeholder="Contoh: Dicoding, Coursera, dll.">
                        </div>
                        <div>
                            <label class="ui-label">Tanggal Terbit</label>
                            <input type="date" name="issue_date" required class="ui-input">
                        </div>
                        <div>
                            <label class="ui-label">File <span class="font-normal text-slate-400">(Wajib PDF – Maks 5MB)</span></label>
                            <input type="file" name="file" required accept=".pdf,application/pdf" class="ui-input file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-blue-700">
                        </div>
                        <div class="flex gap-3 pt-2 pb-1">
                            <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Unggah</button>
                            <button type="button" onclick="closeUploadModal()" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !document.getElementById('uploadModal').classList.contains('hidden')) {
            closeUploadModal();
        }
    });
    </script>
    @endpush
</x-app-layout>
