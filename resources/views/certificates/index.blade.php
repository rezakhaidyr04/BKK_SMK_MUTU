<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Sertifikat Saya" subtitle="Tunjukkan pencapaian dan kualifikasi Anda.">
            <x-slot:actions>
                <button
                    type="button"
                    onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Unggah Sertifikat
                </button>
            </x-slot:actions>
        </x-ui.page-hero>

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
                        <h3 class="font-bold text-slate-900 mb-1">{{ $cert->title }}</h3>
                        <p class="text-sm text-slate-600 mb-1">{{ $cert->issuer }}</p>
                        <p class="text-xs text-slate-400 mb-4">{{ $cert->issue_date->format('M Y') }}</p>
                        <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 text-sm font-medium hover:text-red-700 transition-colors">Hapus</button>
                        </form>
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
                            onclick="document.getElementById('uploadModal').classList.remove('hidden')"
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
    <div id="uploadModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Unggah Sertifikat</h3>
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
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
                    <label class="ui-label">File <span class="font-normal text-slate-400">(PDF, JPG, PNG – Maks 5MB)</span></label>
                    <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png" class="ui-input file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-blue-700">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Unggah</button>
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
