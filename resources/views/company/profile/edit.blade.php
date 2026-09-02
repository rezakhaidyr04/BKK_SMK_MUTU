@extends('layouts.app')

@section('content')
<div class="company-profile-container">

    @if (session('success'))
        <div class="ui-alert ui-alert-success mb-4">
            Berhasil: {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="ui-alert ui-alert-error mb-4">
            Gagal: {{ session('error') }}
        </div>
    @endif

    {{-- PROFIL PERUSAHAAN --}}
    <div class="profile-card p-7 mb-6">
        <div class="section-header flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 m-0">Profil Perusahaan</h1>
                <p class="text-xs text-slate-500 mt-1 mb-0">Kelola data perusahaan Anda.</p>
            </div>
            <div style="width: 3rem; height: 3rem; border-radius: 0.75rem; background: linear-gradient(135deg, #3b82f6, var(--primary-dark)); color: var(--white); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);">
                {{ strtoupper(substr($company->name ?? auth()->user()->name, 0, 1)) }}
            </div>
        </div>

        <form method="POST" action="{{ route('company.profile.update') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Logo Perusahaan --}}
            <div>
                <label class="form-label">Logo Perusahaan
                    <span class="text-slate-400 font-normal normal-case tracking-normal">(JPG, PNG, WebP - maks 2MB, otomatis dikompresi)</span>
                </label>
                <div class="flex items-center gap-4 flex-wrap">
                    <div id="logo-preview-wrap" style="width:4rem; height:4rem; border-radius:0.75rem; border:1.5px solid var(--border); overflow:hidden; background:var(--bg-soft); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        @if($company->logo)
                            <img id="logo-preview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;" />
                        @else
                            <span id="logo-preview" style="font-size:1rem; color:var(--text-3);">Logo</span>
                        @endif
                    </div>
                    <label for="logo-input" class="file-upload-area flex-1 min-w-48">
                        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.25rem; pointer-events:none;">
                            <svg style="width:1.5rem;height:1.5rem;color:var(--text-3);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <p id="logo-label" style="font-size:0.8rem; font-weight:600; color:#64748b; margin:0;">Klik untuk pilih logo</p>
                            <p style="font-size:0.7rem; color:var(--text-3); margin:0;">Akan dikompresi otomatis ke WebP</p>
                        </div>
                        <input id="logo-input" type="file" name="logo" accept="image/*" class="sr-only" onchange="previewLogo(this)" />
                    </label>
                </div>
                @error('logo')<p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" class="form-input" placeholder="PT Nama Perusahaan" />
                @error('name')<p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}" class="form-input" placeholder="email@perusahaan.com" />
                </div>
                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone ?? '') }}" class="form-input" placeholder="021-XXXXXXX" />
                </div>
            </div>

            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
                <div>
                    <label class="form-label">Industri</label>
                    <input type="text" name="industry" value="{{ old('industry', $company->industry ?? '') }}" class="form-input" placeholder="Teknologi, Manufaktur, dsb." />
                </div>
                <div>
                    <label class="form-label">Website</label>
                    <input type="url" name="website" value="{{ old('website', $company->website ?? '') }}" class="form-input" placeholder="https://perusahaan.com" />
                </div>
            </div>


            <div>
                <label class="form-label">Alamat</label>
                <textarea name="address" rows="3" class="form-input" style="resize:vertical;" placeholder="Jl. Contoh No. 1, Kota...">{{ old('address', $company->address ?? '') }}</textarea>
            </div>

            <div>
                <label class="form-label">Deskripsi Perusahaan</label>
                <textarea name="description" rows="4" class="form-input" style="resize:vertical;" placeholder="Ceritakan sedikit tentang perusahaan Anda...">{{ old('description', $company->description ?? '') }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    {{-- VERIFIKASI PERUSAHAAN --}}
    <div id="verification" class="profile-card p-7">
        <div class="section-header flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900 m-0">Status Akun & Verifikasi</h2>
                <p class="text-xs text-slate-500 mt-1 mb-0">Informasi status akun dan kerjasama dengan sekolah.</p>
            </div>
            @php
                $vs = $company->verification_status ?? 'not_submitted';
                $statusLabel = match($vs) {
                    'pending'  => 'Menunggu Review',
                    'verified' => 'Terverifikasi',
                    'rejected' => 'Ditolak',
                    default    => 'Belum Diajukan',
                };
                $statusClass = match($vs) {
                    'pending'  => 'badge-pending',
                    'verified' => 'badge-verified',
                    'rejected' => 'badge-rejected',
                    default    => 'badge-not_submitted',
                };
            @endphp
            <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        {{-- Status Dokumen Kerjasama / MoU --}}
        @if($company->mou_path)
            <div class="profile-document-box">
                <p class="profile-eyebrow">Dokumen Kerjasama (MoU)</p>
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="doc-preview-link opacity-70" title="Silakan hubungi admin jika ingin mengunduh ulang">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Surat MoU Telah Terunggah
                    </span>
                    @if($company->mou_number)
                        <span class="text-xs text-slate-600">No: {{ $company->mou_number }}</span>
                    @endif
                </div>
            </div>
        @endif

        @if($company->is_verified ?? false)
            <div class="ui-alert ui-alert-success">
                <svg class="h-6 w-6 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-semibold">Perusahaan Anda sudah terverifikasi. Semua fitur perekrutan telah aktif.</p>
            </div>
        @else
            @if($company->verification_status === 'pending')
                <div class="ui-alert ui-alert-warning mb-6">
                    <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 01-18-9z" /></svg>
                        <p class="text-sm font-semibold">Akun Anda sedang ditinjau oleh Admin. Silakan tunggu informasi lebih lanjut.</p>
                </div>
            @endif

            @if($company->verification_status === 'rejected')
                <div class="ui-alert ui-alert-error mb-6">
                    <div>
                    <p class="text-sm font-bold mb-1">Verifikasi ditolak.</p>
                    @if($company->rejection_reason)
                        <p class="text-xs">Alasan: {{ $company->rejection_reason }}</p>
                    @endif
                    <p class="text-xs mt-1">Silakan hubungi BKK Sekolah untuk informasi perbaikan dokumen.</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    document.getElementById('logo-label').textContent = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
        const wrap = document.getElementById('logo-preview-wrap');
        // Selalu ganti dengan <img> preview
        wrap.innerHTML = '<img id="logo-preview" src="' + e.target.result + '" alt="Preview" style="width:100%;height:100%;object-fit:contain;opacity:0;transition:opacity 0.3s;" />';
        setTimeout(() => {
            const img = wrap.querySelector('img');
            if (img) img.style.opacity = '1';
        }, 50);
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
