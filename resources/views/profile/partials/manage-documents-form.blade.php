<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Kelengkapan Berkas') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Unggah dokumen pendukung untuk melengkapi profil lamaran Anda (KTP, Ijazah, SKCK, dll).') }}
        </p>
    </header>

    @if (session('success'))
        <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 space-y-4">
        @if($user->documents->count() > 0)
            <div class="divide-y divide-gray-200 rounded-lg border border-gray-200">
                @foreach($user->documents as $doc)
                    <div class="flex items-center justify-between p-3 text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">{{ $doc->document_type }}</p>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                    {{ $doc->original_name }}
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Belum ada dokumen yang diunggah.</p>
        @endif
    </div>

    <form method="post" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        <div>
            <x-input-label for="document_type" :value="__('Jenis Dokumen (Misal: KTP, Ijazah)')" />
            <x-text-input id="document_type" name="document_type" type="text" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('document_type')" />
        </div>

        <div>
            <x-input-label for="file" :value="__('File Dokumen (PDF, JPG, PNG - Max 5MB)')" />
            <input id="file" name="file" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
            <x-input-error class="mt-2" :messages="$errors->get('file')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Unggah') }}</x-primary-button>
        </div>
    </form>
</section>
