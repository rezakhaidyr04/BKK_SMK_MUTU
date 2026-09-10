<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Profil Perusahaan" subtitle="Kelola data dan verifikasi perusahaan Anda." />
        <div class="page-container page-section">

    @if (session('success'))
        <div class="ui-alert ui-alert-success mb-4">Berhasil: {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="ui-alert ui-alert-error mb-4">Gagal: {{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KIRI: Data Perusahaan --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                {{-- Header Data Perusahaan --}}
                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Data Perusahaan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Lengkapi informasi perusahaan Anda dengan benar dan valid.</p>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Card Nama Perusahaan dengan Logo --}}
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-[#0f2040] flex items-center justify-center overflow-hidden flex-shrink-0 border border-slate-200">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo {{ $company->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-white">
                                    <svg class="w-6 h-6 mb-0.5" viewBox="0 0 24 24" fill="currentColor"><ellipse cx="12" cy="12" rx="9" ry="5.5" fill="none" stroke="white" stroke-width="1.2"/><ellipse cx="12" cy="12" rx="4" ry="2.5" fill="none" stroke="white" stroke-width="1.2"/><circle cx="12" cy="12" r="1.2" fill="white"/></svg>
                                    <span class="text-[8px] font-bold tracking-widest">TOYOTA</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $company->name ?? auth()->user()->name }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">Perusahaan</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $company->description ? Str::limit($company->description, 80) : 'Salah satu produsen otomotif terbesar di dunia.' }}</p>
                        </div>
                    </div>

                    {{-- Preview info box --}}
                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl px-4 py-3 flex gap-3">
                        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-700">Preview logo perusahaan</p>
                            <p class="text-[11px] text-blue-600/70">Logo akan ditampilkan pada profil perusahaan Anda.</p>
                        </div>
                    </div>

                    {{-- Upload Area bulat --}}
                    <div class="flex items-center gap-4">
                        <div id="logo-preview-wrap" class="w-16 h-16 rounded-full border-2 border-dashed border-slate-300 overflow-hidden bg-slate-50 flex items-center justify-center flex-shrink-0">
                            @if($company->logo)
                                <img id="logo-preview-static" src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <span id="logo-preview-static" class="text-xs text-slate-400">Logo</span>
                            @endif
                        </div>
                        <label for="logo-input" class="flex-1 cursor-pointer">
                            <div class="border border-slate-200 rounded-xl bg-white px-4 py-3 flex flex-col items-center justify-center gap-1 hover:border-blue-300 hover:bg-blue-50/30 transition">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p id="logo-label" class="text-xs font-semibold text-slate-600">Klik untuk pilih logo</p>
                                <p class="text-[10px] text-slate-400">Geser & zoom, otomatis WebP bulat</p>
                            </div>
                            <input id="logo-input" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="previewLogoWithCrop(event)" />
                            <input type="hidden" name="logo_cropped" id="logo-cropped-flag" value="0">
                        </label>
                        <input type="file" name="logo" id="logo-file-input" class="sr-only" />
                    </div>
                    <div id="logo-crop-preview" class="hidden flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-xl">
                        <img id="logo-crop-thumb" src="" alt="Preview" class="w-12 h-12 rounded-full object-cover border border-blue-200 bg-white">
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-blue-700">Preview bulat siap upload</p>
                            <p class="text-[11px] text-blue-600/70">Sudah dipotong lingkaran.</p>
                        </div>
                        <button type="button" onclick="clearLogoCrop()" class="text-xs text-slate-500 hover:text-red-600">Hapus</button>
                    </div>
                    @error('logo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data" class="space-y-4 pt-2">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Nama Perusahaan
                                </label>
                                <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="PT Nama Perusahaan" />
                                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                                    Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="email@perusahaan.com" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                                    Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $company->phone ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="021-XXXXXXX" />
                            </div>
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 011 1v3a1 1 0 01-1 1h-.01a1 1 0 01-1-1v-3z" clip-rule="evenodd"/></svg>
                                    Industri
                                </label>
                                <div class="relative">
                                    <select name="industry" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none appearance-none">
                                        <option value="">Pilih industri</option>
                                        <option value="Teknologi" {{ old('industry', $company->industry)=='Teknologi'?'selected':'' }}>Teknologi</option>
                                        <option value="Manufaktur" {{ old('industry', $company->industry)=='Manufaktur'?'selected':'' }}>Manufaktur</option>
                                        <option value="Teknologi sistem" {{ old('industry', $company->industry)=='Teknologi sistem'?'selected':'' }}>Teknologi sistem</option>
                                        <option value="Jasa" {{ old('industry', $company->industry)=='Jasa'?'selected':'' }}>Jasa</option>
                                        <option value="Lainnya" {{ old('industry', $company->industry)=='Lainnya'?'selected':'' }}>Lainnya</option>
                                    </select>
                                    <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    Website
                                </label>
                                <input type="url" name="website" value="{{ old('website', $company->website ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="https://perusahaan.com" />
                            </div>
                            <div>
                                <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    Alamat
                                </label>
                                <input type="text" name="address" value="{{ old('address', $company->address ?? '') }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Jl. Contoh No.1" />
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mb-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Deskripsi Perusahaan
                            </label>
                            <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none resize-y" placeholder="Ceritakan tentang perusahaan Anda...">{{ old('description', $company->description ?? '') }}</textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KANAN: Status --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7.001c0-.682.057-1.35.166-2.002zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Status Akun & Verifikasi</h3>
                        <p class="text-[11px] text-slate-400">Informasi status akun dan kerjasama dengan sekolah.</p>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    <a href="#mou" class="flex items-center justify-between p-3 bg-blue-50/50 border border-blue-100 rounded-xl hover:bg-blue-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-700">Dokumen Kerjasama (MoU)</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-slate-700">{{ $company->mou_path ? 'Surat MoU Telah Terunggah' : 'Belum ada MoU' }}</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>

                    @if($company->mou_path && $company->mou_number)
                        <p class="text-xs text-slate-500 px-1">No: {{ $company->mou_number }}</p>
                    @endif

                    @if($company->is_verified ?? false)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-green-700">Perusahaan Anda sudah terverifikasi.</p>
                                <p class="text-[11px] text-green-600/80">Semua fitur perekrutan telah aktif.</p>
                            </div>
                        </div>
                    @else
                        @if(($company->verification_status ?? '') === 'pending')
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex gap-2.5">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-xs font-semibold text-amber-700">Akun Anda sedang ditinjau oleh Admin.</p>
                            </div>
                        @endif
                        @if(($company->verification_status ?? '') === 'rejected')
                            <div class="bg-red-50 border border-red-200 rounded-xl p-3">
                                <p class="text-xs font-bold text-red-700">Verifikasi ditolak.</p>
                                @if($company->rejection_reason)<p class="text-[11px] text-red-600 mt-1">Alasan: {{ $company->rejection_reason }}</p>@endif
                            </div>
                        @endif
                    @endif

                    {{-- Ilustrasi --}}
                    <div class="pt-4 flex flex-col items-center text-center">
                        <div class="w-48 h-36 relative">
                            <svg viewBox="0 0 200 140" class="w-full h-full">
                                <ellipse cx="100" cy="125" rx="60" ry="8" fill="#f1f5f9"/>
                                <rect x="55" y="15" width="70" height="90" rx="8" fill="#dbeafe" stroke="#bfdbfe"/>
                                <rect x="62" y="25" width="45" height="6" rx="3" fill="white"/>
                                <rect x="62" y="35" width="35" height="4" rx="2" fill="white" opacity="0.7"/>
                                <rect x="62" y="45" width="56" height="3" rx="1.5" fill="white" opacity="0.5"/>
                                <rect x="62" y="51" width="56" height="3" rx="1.5" fill="white" opacity="0.5"/>
                                <rect x="62" y="57" width="40" height="3" rx="1.5" fill="white" opacity="0.5"/>
                                <path d="M30 50 Q20 70 30 90 Q35 85 40 90 Q45 60 30 50" fill="#bfdbfe"/>
                                <path d="M170 40 Q180 65 165 85 Q160 80 155 85 Q150 55 170 40" fill="#bfdbfe"/>
                                <circle cx="35" cy="35" r="4" fill="#dbeafe"/>
                                <circle cx="165" cy="25" r="3" fill="#dbeafe"/>
                                <g transform="translate(110,75)">
                                    <path d="M0 -25 L22 -12 L22 18 L0 30 L-22 18 L-22 -12 Z" fill="#2563eb" stroke="#1d4ed8" stroke-width="1.5"/>
                                    <path d="M-8 5 L0 12 L10 -2" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        </div>
                        <p class="text-sm font-handwriting text-blue-600 italic">Terima kasih telah bergabung<br>dengan BKK SMK MUTU</p>
                        <div class="mt-1 w-12 h-0.5 bg-blue-600 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
        </div>
    </div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .cropper-view-box, .cropper-face { border-radius: 50%; }
    #logoCropperModal .cropper-container { max-height: 60vh; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let logoCropper = null;
function previewLogoWithCrop(event) {
    const file = event.target.files[0];
    if (!file) return;
    document.getElementById('logo-label').textContent = file.name;
    const reader = new FileReader();
    reader.onload = function(e) {
        const modal = document.getElementById('logoCropperModal');
        const img = document.getElementById('logoImageToCrop');
        img.src = e.target.result;
        img.classList.remove('hidden');
        modal.classList.remove('hidden');
        if (logoCropper) { logoCropper.destroy(); logoCropper = null; }
        logoCropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.85,
            cropBoxMovable: true,
            cropBoxResizable: true,
            guides: true,
            center: true,
            zoomable: true,
            scalable: true,
            background: false,
        });
    };
    reader.readAsDataURL(file);
    event.target.value = '';
}
function closeLogoCropper() {
    document.getElementById('logoCropperModal').classList.add('hidden');
    if (logoCropper) { logoCropper.destroy(); logoCropper = null; }
}
function applyLogoCrop() {
    if (!logoCropper) return;
    logoCropper.getCroppedCanvas({ width: 400, height: 400, imageSmoothingQuality: 'high' }).toBlob((blob) => {
        const file = new File([blob], 'logo_cropped.webp', { type: 'image/webp' });
        const dt = new DataTransfer();
        dt.items.add(file);
        const realInput = document.getElementById('logo-file-input');
        realInput.files = dt.files;
        document.getElementById('logo-cropped-flag').value = '1';
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.getElementById('logo-preview-wrap');
            wrap.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 0.3s;" />';
            setTimeout(() => { const img = wrap.querySelector('img'); if(img) img.style.opacity='1'; }, 50);
            const thumb = document.getElementById('logo-crop-thumb');
            const box = document.getElementById('logo-crop-preview');
            thumb.src = e.target.result;
            box.classList.remove('hidden');
            document.getElementById('logo-label').textContent = 'Logo siap upload (sudah dipotong)';
        };
        reader.readAsDataURL(file);
        closeLogoCropper();
    }, 'image/webp', 0.85);
}
function clearLogoCrop() {
    document.getElementById('logo-file-input').value = '';
    document.getElementById('logo-cropped-flag').value = '0';
    document.getElementById('logo-crop-preview').classList.add('hidden');
    document.getElementById('logo-label').textContent = 'Klik untuk pilih logo';
}
function logoZoomIn(){ if(logoCropper) logoCropper.zoom(0.1); }
function logoZoomOut(){ if(logoCropper) logoCropper.zoom(-0.1); }
function logoReset(){ if(logoCropper) logoCropper.reset(); }
</script>
@endpush

<div id="logoCropperModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white p-6 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
        <h3 class="text-lg font-bold text-slate-900 mb-1">Sesuaikan Logo Perusahaan</h3>
        <p class="text-xs text-slate-500 mb-4">Geser untuk mengatur posisi, pinch/scroll untuk zoom, tarik sudut untuk ubah ukuran kotak.</p>
        <div class="max-h-[60vh] overflow-hidden rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200">
            <img id="logoImageToCrop" src="" class="max-w-full hidden">
        </div>
        <div class="mt-3 flex items-center justify-center gap-2">
            <button type="button" onclick="logoZoomOut()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-semibold">− Zoom</button>
            <button type="button" onclick="logoReset()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</button>
            <button type="button" onclick="logoZoomIn()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-semibold">+ Zoom</button>
        </div>
        <div class="mt-4 flex justify-end gap-3">
            <button type="button" onclick="closeLogoCropper()" class="px-5 py-2.5 text-slate-600 font-medium hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">Batal</button>
            <button type="button" onclick="applyLogoCrop()" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl shadow-sm hover:bg-blue-700 transition">Simpan Potongan</button>
        </div>
    </div>
</div>
</x-app-layout>
