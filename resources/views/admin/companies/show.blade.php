<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detail Perusahaan" subtitle="{{ $company->name }}">
            <x-slot:actions>
                <x-ui.status-badge :status="$company->verification_status ?? ($company->is_verified ? 'verified' : 'pending')" />
                <x-ui.btn href="{{ route('admin.companies.edit', $company) }}" variant="white" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </x-ui.btn>
                <x-ui.btn href="{{ route('admin.companies.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

<div class="page-container page-section">

            {{-- ════════════════════════════════════════════════════════
                 Account created notification
                 ═══════════════════════════════════════════════════════ --}}

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <ul class="text-sm text-red-700 space-y-1 list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            {{-- Password awal hanya ditampilkan sekali — session data akan otomatis hilang setelah dibaca --}}
            @if(session('initial_password'))
            <div class="mb-6 bg-blue-50 border-2 border-blue-400 rounded-2xl overflow-hidden shadow-lg">
                <div class="px-5 py-3 bg-blue-400 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2m4 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="font-semibold text-blue-500">PASSWORD AWAL AKUN</span>
                    </div>
                        <button onclick="document.querySelector('.initial-password-panel').remove()"
                            class="text-blue-500 hover:text-blue-900 transition text-lg font-bold">&times;</button>
                </div>
                <div class="p-5">
                    <p class="text-sm text-blue-500 mb-3">Password ini hanya ditampilkan <strong>satu kali</strong> setelah pembuatan akun.</p>
                    <div class="bg-white rounded-xl p-4 mb-4">
                        <code class="text-sm font-mono text-blue-500 block word-break" id="initialPass">{{ session('initial_password') }}</code>
                    </div>
                    <p class="text-sm text-blue-600">
                        <strong>Disarankan:</strong> Ganti password setelah login pertama untuk keamanan maksimal.
                    </p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- ── Sidebar (Profil & Aksi) ── --}}
                <div class="lg:col-span-1 space-y-5">

                    {{-- Identitas --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 text-center border-b border-gray-100 bg-gradient-to-br from-blue-50 to-violet-50">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold mb-3">
                                {{ substr($company->name, 0, 1) }}
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $company->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $company->industry ?? 'Industri belum diisi' }}</p>
                            <div class="mt-3">
                                <x-ui.status-badge :status="$company->verification_status ?? ($company->is_verified ? 'verified' : 'pending')" />
                            </div>
                        </div>
                        <div class="p-5 space-y-3 text-sm">
                            @if(optional($company->user)->email ?? $company->email)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-700">{{ optional($company->user)->email ?? $company->email }}</span>
                            </div>
                            @endif

                            @if($company->phone)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-gray-700">{{ $company->phone }}</span>
                            </div>
                            @endif

                            @if($company->website)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $company->website }}</a>
                            </div>
                            @endif

                            @if($company->address)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $company->address }}</span>
                            </div>
                            @endif



                            <div class="pt-3 border-t border-gray-100 space-y-1.5 text-xs text-gray-500">
                                <div class="flex justify-between">
                                    <span>Terdaftar</span>
                                    <span class="font-medium text-gray-900">{{ $company->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Lowongan</span>
                                    <span class="font-bold text-blue-600">{{ $company->jobs->count() }}</span>
                                </div>
                                @if($company->reviewed_at)
                                <div class="flex justify-between">
                                    <span>Direview</span>
                                    <span class="font-medium text-gray-900">{{ $company->reviewed_at->format('d M Y') }}</span>
                                </div>
                                @if($company->reviewer)
                                <div class="flex justify-between">
                                    <span>Oleh</span>
                                    <span class="font-medium text-gray-900">{{ $company->reviewer->name }}</span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Aksi Verifikasi --}}
                    @if($company->verification_status !== 'verified')
                    <div class="bg-white rounded-2xl shadow-lg p-5 space-y-3">
                        <h4 class="font-bold text-gray-900 text-sm">Aksi Verifikasi</h4>

                        <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Setujui verifikasi {{ addslashes($company->name) }}?')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Setujui Verifikasi
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($company->verification_status !== 'rejected')
                    <div class="bg-white rounded-2xl shadow-lg p-5">
                        <button type="button"
                                onclick="openRejectModal({{ $company->id }}, '{{ addslashes($company->name) }}')"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tolak Verifikasi
                        </button>
                    </div>
                    @endif

                    @if($company->verification_status === 'rejected' && $company->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
                        <p class="text-xs font-bold text-red-700 mb-1">Alasan Penolakan:</p>
                        <p class="text-sm text-red-700">{{ $company->rejection_reason }}</p>
                    </div>
                    @endif

                    {{-- ── Buat Akun Perusahaan (Phase 3) ── --}}
                    @if($company->isApproved() && !$company->hasUserAccount())
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-200">
                        <div class="px-5 py-3 bg-gradient-to-r from-blue-600 to-violet-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <span class="font-bold text-white text-sm">Buat Akun Perusahaan</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.companies.create-account', $company) }}"
                              class="p-5 space-y-3">
                            @csrf
                            <div>
                                <label for="create_email" class="block text-xs font-semibold text-gray-700 mb-1">Email Login</label>
                                <input type="email" id="create_email" name="email"
                                       value="{{ old('email', $company->email) }}"
                                       placeholder="hrd@perusahaan.com"
                                       required
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition {{ $errors->has('email') ? 'border-red-400' : '' }}">
                                @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-400 mt-1">Email ini akan menjadi username login perusahaan.</p>
                            </div>
                            <button type="submit"
                                    onclick="return confirm('Buat akun login untuk {{ addslashes($company->name) }}?\n\nPassword sementara akan ditampilkan SEKALI. Pastikan Anda siap mencatatnya.')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Buat Akun & Tampilkan Password
                            </button>
                        </form>
                    </div>
                    @elseif($company->hasUserAccount())
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs font-bold text-green-800">Akun Sudah Ada</p>
                        </div>
                        <p class="text-xs text-green-700">{{ optional($company->user)->email }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            @if(optional($company->user)->must_change_password)
                                Status: Belum ganti password
                            @else
                                Status: Password sudah diganti ✓
                            @endif
                        </p>
                    </div>
                    @elseif(!$company->isApproved())
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
                        <p class="text-xs text-gray-500 text-center">Akun dapat dibuat setelah perusahaan disetujui.</p>
                    </div>
                    @endif

                </div>

                {{-- ── Konten Utama ── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Deskripsi --}}
                    @if($company->description)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Deskripsi Perusahaan</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $company->description }}</p>
                    </div>
                    @endif

                    {{-- Surat MoU --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-green-50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-900">Surat MoU / Perjanjian Kerjasama</h3>
                            </div>
                            @if($company->mou_path)
                            <a href="{{ route('admin.companies.mou.download', $company) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download MoU
                            </a>
                            @endif
                        </div>

                        <div class="p-6">
                            @if($company->mou_path)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500">Status File</p>
                                    <p class="font-bold text-green-700 text-sm mt-1">File Tersedia</p>
                                </div>

                                @if($company->mou_number)
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-500">Nomor MoU</p>
                                    <p class="font-semibold text-gray-900 text-sm mt-1">{{ $company->mou_number }}</p>
                                </div>
                                @endif

                                @if($company->mou_signed_at)
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-500">Ditandatangani</p>
                                    <p class="font-semibold text-gray-900 text-sm mt-1">{{ $company->mou_signed_at->format('d M Y') }}</p>
                                </div>
                                @endif

                                @if($company->mou_expires_at)
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-500">Berakhir</p>
                                    <p class="font-semibold text-sm mt-1 {{ $company->mou_expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $company->mou_expires_at->format('d M Y') }}
                                        @if($company->mou_expires_at->isPast())
                                        <span class="text-xs text-red-500 block mt-0.5">Sudah kadaluarsa</span>
                                        @elseif($company->mou_expires_at->diffInDays(now()) < 30)
                                        <span class="text-xs text-amber-600 block mt-0.5">Segera berakhir</span>
                                        @endif
                                    </p>
                                </div>
                                @endif
                            </div>

                            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2">
                                <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <p class="text-xs text-amber-700">
                                    File MoU disimpan secara <strong>privat</strong>. Hanya admin yang dapat mengunduh file ini.
                                    URL tidak dapat diakses langsung.
                                </p>
                            </div>

                            @else
                            <div class="text-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Belum ada file MoU yang diunggah.</p>
                                <a href="{{ route('admin.companies.edit', $company) }}"
                                   class="mt-3 inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Upload MoU via Edit →
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Dokumen Legalitas Lama --}}
                    @if($company->business_license_path || $company->operating_license_path)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Berkas Verifikasi (Legalitas)</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Dokumen yang diunggah oleh perusahaan</p>
                        </div>
                        <div class="p-5 space-y-2">

                            @if($company->business_license_path)
                            <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Izin Usaha (SIUP)</span>
                                <a href="{{ route('admin.companies.documents.download', [$company, 'business-license']) }}"
                                   class="text-xs text-blue-600 font-semibold hover:underline">Lihat →</a>
                            </div>
                            @endif
                            @if($company->operating_license_path)
                            <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700">Izin Operasional</span>
                                <a href="{{ route('admin.companies.documents.download', [$company, 'operating-license']) }}"
                                   class="text-xs text-blue-600 font-semibold hover:underline">Lihat →</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Lowongan --}}
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Lowongan Terbaru</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($company->jobs as $job)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ \App\Support\Label::jobStatus($job->status) }} · {{ $job->location }}</p>
                                    </div>
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-xs text-blue-600 font-semibold hover:underline flex-shrink-0">Detail</a>
                                </div>
                                @if($job->description)
                                <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($job->description, 100) }}</p>
                                @endif
                            </div>
                            @empty
                            <div class="p-10 text-center">
                                <p class="text-gray-400 text-sm">Belum ada lowongan.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>

    {{-- Reject Modal --}}
    <x-ui.modal id="rejectModal" title="Tolak Verifikasi">
        <p class="text-sm text-slate-500 mb-4">Perusahaan: <span id="rejectCompanyName" class="font-semibold text-slate-800"></span></p>

        <form id="rejectForm" method="POST" class="ui-form-stack">
            @csrf
            <div>
                <label class="ui-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" rows="4" required maxlength="500" class="ui-textarea"
                          placeholder="Contoh: Profil perusahaan belum lengkap. Mohon isi industri, alamat, dan deskripsi perusahaan terlebih dahulu."></textarea>
                <p class="text-xs text-slate-400 mt-1">Alasan ini akan ditampilkan ke perusahaan. Maks 500 karakter.</p>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn variant="danger" type="submit">Kirim Penolakan</x-ui.btn>
                <x-ui.btn variant="secondary" type="button" onclick="closeRejectModal()">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.modal>

    @push('scripts')
    <script>
    function openRejectModal(companyId, companyName) {
        document.getElementById('rejectCompanyName').textContent = companyName;
        document.getElementById('rejectForm').action = `/admin/companies/${companyId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').querySelector('textarea').value = '';
    }
    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', closeRejectModal);
    });

    // Copy to clipboard — untuk one-time password panel
    function copyText(sourceId, btnId) {
        const text = document.getElementById(sourceId)?.textContent?.trim();
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById(btnId);
            if (btn) {
                const orig = btn.textContent;
                btn.textContent = 'Tersalin ✓';
                btn.classList.add('text-green-600');
                setTimeout(() => {
                    btn.textContent = orig;
                    btn.classList.remove('text-green-600');
                }, 2000);
            }
        }).catch(() => {
            // Fallback untuk browser lama
            const el = document.getElementById(sourceId);
            const range = document.createRange();
            range.selectNode(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
        });
    }
    </script>
    @endpush
</x-app-layout>
