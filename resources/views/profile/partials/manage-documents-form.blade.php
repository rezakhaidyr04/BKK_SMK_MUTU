<section>
    <header class="border-b border-slate-100 pb-4 mb-6">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">
            {{ __('Kelengkapan Berkas') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Unggah berkas pendukung untuk melengkapi profil lamaran Anda seperti CV, KTP, atau Ijazah.') }}
        </p>
    </header>

    @if (session('success'))
        <div class="mb-5 p-4 text-sm text-green-700 bg-green-50 rounded-xl border border-green-100 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        @if($user->documents->count() > 0)
            <div class="divide-y divide-slate-100 rounded-2xl border border-slate-100 bg-slate-50/30 overflow-hidden">
                @foreach($user->documents as $doc)
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-sm truncate">{{ $doc->document_type }}</p>
                                <a href="{{ route('documents.download', $doc) }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline truncate block max-w-xs sm:max-w-md">
                                    {{ $doc->original_name }}
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');" class="flex-shrink-0 pl-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus Dokumen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 px-4 border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                </svg>
                <p class="text-xs font-semibold text-slate-400">Belum ada dokumen berkas yang diunggah.</p>
            </div>
        @endif
    </div>

    <form method="post" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4 border-t border-slate-100 pt-5">
        @csrf
        <div>
            <x-input-label for="document_type" :value="__('Nama / Jenis Dokumen')" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="document_type" name="document_type" type="text" class="mt-1 block w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Contoh: KTP, Ijazah, CV" required />
            <x-input-error class="mt-1.5" :messages="$errors->get('document_type')" />
        </div>

        <div>
            <x-input-label for="file" :value="__('File Dokumen')" class="text-slate-700 font-semibold mb-1" />
            <div class="mt-1">
                <input id="file" name="file" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer" required />
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5">Mendukung format PDF, JPG, PNG. Ukuran maksimal file 5MB.</p>
            <x-input-error class="mt-1.5" :messages="$errors->get('file')" />
        </div>

        <div class="pt-2">
            <x-primary-button class="rounded-xl px-5 py-2.5 bg-blue-600 hover:bg-blue-700 shadow-sm transition w-full justify-center">{{ __('Unggah Berkas') }}</x-primary-button>
        </div>
    </form>
</section>
