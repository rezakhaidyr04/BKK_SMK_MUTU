@extends('layouts.app')

@section('content')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }
    .section-header {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-input {
        display: block;
        width: 100%;
        padding: 0.5rem 0.875rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        color: #0f172a;
        background: #fafafa;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .file-upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        background: #f8fafc;
        transition: all 0.2s;
        cursor: pointer;
    }
    .file-upload-area:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-verified { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-not_submitted { background: #f1f5f9; color: #475569; }
    .doc-preview-link {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        transition: all 0.2s;
    }
    .doc-preview-link:hover { background: #dbeafe; }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 10px -1px rgba(37,99,235,0.35); }
    .btn-success {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        padding: 0.5rem 1.25rem;
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(5,150,105,0.3);
    }
    .btn-success:hover { transform: translateY(-1px); box-shadow: 0 6px 10px -1px rgba(5,150,105,0.35); }
</style>

<div style="max-width: 64rem; margin: 0 auto; padding: 2rem 1rem;">

    @if (session('success'))
        <div style="margin-bottom: 1rem; border-radius: 12px; background: #d1fae5; border: 1px solid #6ee7b7; padding: 0.875rem 1rem; font-size: 0.875rem; color: #065f46; font-weight: 500;">
            Berhasil: {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="margin-bottom: 1rem; border-radius: 12px; background: #fee2e2; border: 1px solid #fca5a5; padding: 0.875rem 1rem; font-size: 0.875rem; color: #991b1b; font-weight: 500;">
            Gagal: {{ session('error') }}
        </div>
    @endif

    {{-- PROFIL PERUSAHAAN --}}
    <div class="profile-card" style="padding: 1.75rem; margin-bottom: 1.5rem;">
        <div class="section-header flex items-center justify-between">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin:0;">Profil Perusahaan</h1>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0;">Kelola data perusahaan Anda.</p>
            </div>
            <div style="width: 3rem; height: 3rem; border-radius: 0.75rem; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);">
                {{ strtoupper(substr($company->name ?? auth()->user()->name, 0, 1)) }}
            </div>
        </div>

        <form method="POST" action="{{ route('company.profile.update') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Logo Perusahaan --}}
            <div>
                <label class="form-label">Logo Perusahaan
                    <span style="color:#94a3b8; font-weight:400; text-transform:none; letter-spacing:0;">(JPG, PNG, WebP — maks 2MB, otomatis dikompresi)</span>
                </label>
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                    <div id="logo-preview-wrap" style="width:4rem; height:4rem; border-radius:0.75rem; border:1.5px solid #e2e8f0; overflow:hidden; background:#f8fafc; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        @if($company->logo)
                            <img id="logo-preview" src="{{ asset('storage/' . $company->logo) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;" />
                        @else
                            <span id="logo-preview" style="font-size:1rem; color:#94a3b8;">Logo</span>
                        @endif
                    </div>
                    <div class="file-upload-area" style="flex:1; min-width:12rem;" onclick="document.getElementById('logo-input').click()">
                        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.25rem; pointer-events:none;">
                            <svg style="width:1.5rem;height:1.5rem;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <p id="logo-label" style="font-size:0.8rem; font-weight:600; color:#64748b; margin:0;">Klik untuk pilih logo</p>
                            <p style="font-size:0.7rem; color:#94a3b8; margin:0;">Akan dikompresi otomatis ke WebP</p>
                        </div>
                        <input id="logo-input" type="file" name="logo" accept="image/*" style="display:none;" onchange="previewLogo(this)" />
                    </div>
                </div>
                @error('logo')<p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}" class="form-input" placeholder="PT Nama Perusahaan" />
                @error('name')<p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>@enderror
            </div>

            <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}" class="form-input" placeholder="email@perusahaan.com" />
                </div>
                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $company->phone ?? '') }}" class="form-input" placeholder="021-XXXXXXX" />
                </div>
            </div>

            <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));">
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

            <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    {{-- VERIFIKASI PERUSAHAAN --}}
    <div id="verification" class="profile-card" style="padding: 1.75rem;">
        <div class="section-header flex items-center justify-between">
            <div>
                <h2 style="font-size: 1.125rem; font-weight: 700; color: #0f172a; margin:0;">Status Akun & Verifikasi</h2>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0;">Informasi status akun dan kerjasama dengan sekolah.</p>
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
            <div style="border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 1rem; margin-bottom: 1.5rem;">
                <p style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin: 0 0 0.75rem;">Dokumen Kerjasama (MoU)</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                    <a href="javascript:void(0);" role="button" aria-disabled="true" style="opacity: 0.7; cursor: default;" class="doc-preview-link" title="Silakan hubungi admin jika ingin mengunduh ulang">
                        <svg style="width:0.875rem;height:0.875rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Surat MoU Telah Terunggah
                    </a>
                    @if($company->mou_number)
                        <span style="font-size: 0.8rem; color: #475569;">No: {{ $company->mou_number }}</span>
                    @endif
                </div>
            </div>
        @endif

        @if($company->is_verified ?? false)
            <div style="border-radius: 12px; background: #d1fae5; border: 1px solid #6ee7b7; padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width:1.5rem;height:1.5rem;color:#059669;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p style="font-size:0.875rem; font-weight:600; color:#065f46; margin:0;">Perusahaan Anda sudah terverifikasi. Semua fitur perekrutan telah aktif.</p>
            </div>
        @else
            @if($company->verification_status === 'pending')
                <div style="border-radius: 12px; background: #fef3c7; border: 1px solid #fcd34d; padding: 1rem; display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <svg style="width:1.5rem;height:1.5rem;color:#b45309;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p style="font-size:0.875rem; font-weight:600; color:#92400e; margin:0;">Akun Anda sedang ditinjau oleh Admin. Silakan tunggu informasi lebih lanjut.</p>
                </div>
            @endif

            @if($company->verification_status === 'rejected')
                <div style="border-radius: 12px; background: #fee2e2; border: 1px solid #fca5a5; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="font-size:0.875rem; font-weight:700; color:#991b1b; margin:0 0 0.25rem;">Verifikasi ditolak.</p>
                    @if($company->rejection_reason)
                        <p style="font-size:0.8rem; color:#b91c1c; margin:0;">Alasan: {{ $company->rejection_reason }}</p>
                    @endif
                    <p style="font-size:0.8rem; color:#b91c1c; margin:0.25rem 0 0;">Silakan hubungi BKK Sekolah untuk informasi perbaikan dokumen.</p>
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
