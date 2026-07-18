<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Profil Perusahaan" subtitle="Lengkapi dan perbarui informasi perusahaan Anda.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('dashboard') }}" size="sm">
                    Kembali ke Dasbor
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">

        @if(session('success'))
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        @endif

        <x-ui.form-errors />

        @php
            $status = $company->verification_status ?? 'pending';
        @endphp

        @if($status === 'verified')
            <x-ui.alert type="success">
                <p class="font-bold">Akun Terverifikasi ✓</p>
                <p class="mt-0.5">Perusahaan Anda sudah diverifikasi oleh admin. Anda dapat memposting lowongan.</p>
            </x-ui.alert>
        @elseif($status === 'rejected')
            <x-ui.alert type="danger">
                <p class="font-bold">Verifikasi Ditolak</p>
                @if($company->rejection_reason)
                    <p class="mt-1"><span class="font-semibold">Alasan dari admin:</span><br>{{ $company->rejection_reason }}</p>
                @endif
                <p class="mt-2 font-medium">Perbaiki profil sesuai alasan di atas lalu klik "Simpan Perubahan" — status akan otomatis diajukan ulang ke admin.</p>
            </x-ui.alert>
        @else
            <x-ui.alert type="warning">
                <p class="font-bold">Menunggu Verifikasi Admin</p>
                <p class="mt-0.5">Profil Anda sedang ditinjau oleh admin BKK SMK MUTU. Lengkapi semua informasi di bawah agar verifikasi lebih cepat diproses.</p>
            </x-ui.alert>
        @endif

        <x-ui.panel>
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                @if($company->logo)
                    <img id="logo-preview"
                         src="{{ asset('storage/' . $company->logo) }}"
                         alt="{{ $company->name }}"
                         class="w-16 h-16 rounded-xl object-cover border-2 border-slate-200">
                @else
                    <div id="logo-placeholder"
                         class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                        {{ substr($company->name, 0, 1) }}
                    </div>
                    <img id="logo-preview" src="" alt=""
                         class="w-16 h-16 rounded-xl object-cover border-2 border-slate-200 hidden">
                @endif
                <div>
                    <p class="font-semibold text-slate-900">{{ $company->name }}</p>
                    <div class="mt-1">
                        @if($status === 'verified')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Terverifikasi
                            </span>
                        @elseif($status === 'rejected')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Menunggu verifikasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="ui-form-stack">
                @csrf
                @method('PUT')

                <div>
                    <label class="ui-label">Logo Perusahaan</label>
                    <div class="flex items-center gap-3">
                        <label for="logo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ganti Logo
                        </label>
                        <input id="logo" name="logo" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp" onchange="previewLogo(event)">
                        <p class="text-xs text-slate-400">JPG, PNG, WebP · maks. 2MB</p>
                    </div>
                    @error('logo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="name" class="ui-label">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required class="ui-input">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="industry" class="ui-label">Industri <span class="text-red-500">*</span> <span class="text-slate-400 font-normal ml-1">(Wajib untuk verifikasi)</span></label>
                    <input type="text" id="industry" name="industry" value="{{ old('industry', $company->industry) }}" placeholder="Contoh: Teknologi, Manufaktur..." class="ui-input">
                    @error('industry')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="website" class="ui-label">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $company->website) }}" placeholder="https://perusahaan.com" class="ui-input">
                    @error('website')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="address" class="ui-label">Alamat <span class="text-red-500">*</span> <span class="text-slate-400 font-normal ml-1">(Wajib untuk verifikasi)</span></label>
                    <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}" placeholder="Jl. Nama Jalan No. XX, Kota" class="ui-input">
                    @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description" class="ui-label">Deskripsi Perusahaan <span class="text-red-500">*</span> <span class="text-slate-400 font-normal ml-1">(Wajib untuk verifikasi)</span></label>
                    <textarea id="description" name="description" rows="4" class="ui-textarea" placeholder="Ceritakan tentang perusahaan Anda...">{{ old('description', $company->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                    <x-ui.btn type="submit">
                        {{ $status === 'rejected' ? 'Simpan & Ajukan Ulang' : 'Simpan Perubahan' }}
                    </x-ui.btn>
                </div>
            </form>
        </x-ui.panel>

        @if($status !== 'verified')
        <x-ui.panel>
            <h3 class="text-sm font-semibold text-slate-900 mb-4">Proses Verifikasi</h3>
            <ol class="space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $status !== 'pending' && $status !== 'rejected' ? 'bg-green-500 text-white' : 'bg-blue-600 text-white' }}">1</span>
                    <span class="mt-0.5">Lengkapi profil perusahaan (nama, industri, alamat, deskripsi)</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $status === 'pending' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">2</span>
                    <span class="mt-0.5">Admin BKK SMK MUTU akan meninjau profil Anda</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-slate-200 text-slate-500">3</span>
                    <span class="mt-0.5">Setelah diverifikasi, Anda dapat memposting lowongan kerja</span>
                </li>
            </ol>
        </x-ui.panel>
        @endif

    </div>
</x-app-layout>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-placeholder');
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
