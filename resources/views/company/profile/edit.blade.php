<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil Perusahaan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Flash success --}}
            @if(session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700 flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            {{-- Status Verifikasi --}}
            @php
                $status = $company->verification_status ?? 'pending';
            @endphp

            @if($status === 'verified')
            <div class="rounded-xl border border-green-200 p-4 flex items-start gap-3" style="background:#f0fdf4">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold text-green-800">Akun Terverifikasi ✓</p>
                    <p class="text-sm text-green-700 mt-0.5">Perusahaan Anda sudah diverifikasi oleh admin. Anda dapat memposting lowongan.</p>
                </div>
            </div>

            @elseif($status === 'rejected')
            <div class="rounded-xl border border-red-200 p-4 flex items-start gap-3" style="background:#fef2f2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold text-red-800">Verifikasi Ditolak</p>
                    @if($company->rejection_reason)
                    <p class="text-sm text-red-700 mt-1">
                        <span class="font-semibold">Alasan dari admin:</span><br>
                        {{ $company->rejection_reason }}
                    </p>
                    @endif
                    <p class="text-sm text-red-600 mt-2 font-medium">
                        Perbaiki profil sesuai alasan di atas lalu klik "Simpan Perubahan" — status akan otomatis diajukan ulang ke admin.
                    </p>
                </div>
            </div>

            @else
            <div class="rounded-xl border border-yellow-200 p-4 flex items-start gap-3" style="background:#fefce8">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold text-yellow-800">Menunggu Verifikasi Admin</p>
                    <p class="text-sm text-yellow-700 mt-0.5">
                        Profil Anda sedang ditinjau oleh admin Mutu Career Center. Lengkapi semua informasi di bawah agar verifikasi lebih cepat diproses.
                    </p>
                </div>
            </div>
            @endif

            {{-- Form Profil --}}
            <div class="bg-white shadow sm:rounded-2xl border border-gray-100 p-6">

                {{-- Header logo + nama + status badge --}}
                <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
                    @if($company->logo)
                        <img id="logo-preview"
                             src="{{ asset('storage/' . $company->logo) }}"
                             alt="{{ $company->name }}"
                             class="w-16 h-16 rounded-xl object-cover border-2 border-gray-200">
                    @else
                        <div id="logo-placeholder"
                             class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                            {{ substr($company->name, 0, 1) }}
                        </div>
                        <img id="logo-preview" src="" alt=""
                             class="w-16 h-16 rounded-xl object-cover border-2 border-gray-200 hidden">
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900">{{ $company->name }}</p>
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

                <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Logo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Perusahaan</label>
                        <div class="flex items-center gap-3">
                            <label for="logo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Ganti Logo
                            </label>
                            <input id="logo" name="logo" type="file" class="sr-only"
                                   accept="image/jpeg,image/png,image/webp"
                                   onchange="previewLogo(event)">
                            <p class="text-xs text-gray-400">JPG, PNG, WebP · maks. 2MB</p>
                        </div>
                        @error('logo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $company->name) }}" required
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Industri --}}
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700">
                            Industri <span class="text-red-500 text-xs">*wajib untuk verifikasi</span>
                        </label>
                        <input type="text" id="industry" name="industry"
                               value="{{ old('industry', $company->industry) }}"
                               placeholder="Contoh: Teknologi, Manufaktur, Pendidikan, Kuliner..."
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('industry')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input type="url" id="website" name="website"
                               value="{{ old('website', $company->website) }}"
                               placeholder="https://perusahaan.com"
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('website')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Alamat <span class="text-red-500 text-xs">*wajib untuk verifikasi</span>
                        </label>
                        <input type="text" id="address" name="address"
                               value="{{ old('address', $company->address) }}"
                               placeholder="Jl. Nama Jalan No. XX, Kota"
                               class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Deskripsi Perusahaan <span class="text-red-500 text-xs">*wajib untuk verifikasi</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Ceritakan tentang perusahaan Anda: bidang usaha, visi misi, dan budaya kerja...">{{ old('description', $company->description) }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                            @if($status === 'rejected')
                                Simpan &amp; Ajukan Ulang
                            @else
                                Simpan Perubahan
                            @endif
                        </button>
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-2.5 border border-gray-300 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 transition">
                            Kembali ke Dasbor
                        </a>
                    </div>
                </form>
            </div>

            {{-- Info proses verifikasi --}}
            @if($status !== 'verified')
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Proses Verifikasi</h3>
                <ol class="space-y-2.5 text-sm text-gray-600">
                    <li class="flex items-start gap-2.5">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $status !== 'pending' && $status !== 'rejected' ? 'bg-green-500 text-white' : 'bg-blue-600 text-white' }}">1</span>
                        <span>Lengkapi profil perusahaan (nama, industri, alamat, deskripsi)</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $status === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
                        <span>Admin Mutu Career Center akan meninjau profil Anda</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold bg-gray-200 text-gray-500">3</span>
                        <span>Setelah diverifikasi, Anda dapat memposting lowongan kerja</span>
                    </li>
                </ol>
            </div>
            @endif

        </div>
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
