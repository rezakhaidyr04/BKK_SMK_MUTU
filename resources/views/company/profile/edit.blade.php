<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Profil Perusahaan" subtitle="Kelola data dan verifikasi perusahaan Anda." />
        <div class="page-container page-section">
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
            <div style="width: 3rem; height: 3rem; border-radius: 0.75rem; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" />
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #3b82f6, var(--primary-dark)); color: var(--white); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($company->name ?? auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('company.profile.update') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Logo Perusahaan --}}
            <div>
                <label class="form-label">Logo Perusahaan
                    <span class="text-slate-400 font-normal normal-case tracking-normal">(JPG, PNG, WebP - maks 2MB, geser & zoom untuk menyesuaikan)</span>
                </label>
                <div class="flex items-center gap-4 flex-wrap">
                    <div id="logo-preview-wrap" style="width:4rem; height:4rem; border-radius:0.75rem; border:1.5px solid var(--border); overflow:hidden; background:var(--bg-soft); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        @if($company->logo)
                            <img id="logo-preview-static" src="{{ asset('storage/' . $company->logo) }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;" />
                        @else
                            <span id="logo-preview-static" style="font-size:1rem; color:var(--text-3);">Logo</span>
                        @endif
                    </div>
                    <label for="logo-input" class="file-upload-area flex-1 min-w-48 cursor-pointer">
                        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.25rem; pointer-events:none;">
                            <svg style="width:1.5rem;height:1.5rem;color:var(--text-3);" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            <p id="logo-label" style="font-size:0.8rem; font-weight:600; color:#64748b; margin:0;">Klik untuk pilih logo</p>
                            <p style="font-size:0.7rem; color:var(--text-3); margin:0;">Bisa digeser & di-zoom, otomatis WebP</p>
                        </div>
                        <input id="logo-input" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" onchange="previewLogoWithCrop(event)" />
                        <input type="hidden" name="logo_cropped" id="logo-cropped-flag" value="0">
                    </label>
                    {{-- Hidden actual file input that will be submitted --}}
                    <input type="file" name="logo" id="logo-file-input" class="sr-only" />
                </div>
                <div id="logo-crop-preview" class="hidden mt-3 flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-xl">
                    <img id="logo-crop-thumb" src="" alt="Preview crop" class="w-12 h-12 rounded-lg object-cover border border-blue-200 bg-white">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-blue-700">Preview terpotong siap upload</p>
                        <p class="text-[11px] text-blue-600/70">Geser/zoom di modal untuk menyesuaikan. Klik simpan jika sudah pas.</p>
                    </div>
                    <button type="button" onclick="clearLogoCrop()" class="text-xs text-slate-500 hover:text-red-600">Hapus</button>
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
        </div>
    </div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .cropper-view-box, .cropper-face { border-radius: 0.75rem; }
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
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const wrap = document.getElementById('logo-preview-wrap');
            wrap.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width:100%;height:100%;object-fit:contain;opacity:0;transition:opacity 0.3s;" />';
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

function previewLogo(input) {
    // fallback lama jika dipanggil
    previewLogoWithCrop({ target: input });
}

// Zoom controls
function logoZoomIn(){ if(logoCropper) logoCropper.zoom(0.1); }
function logoZoomOut(){ if(logoCropper) logoCropper.zoom(-0.1); }
function logoReset(){ if(logoCropper) logoCropper.reset(); }
</script>
@endpush

{{-- Modal Cropper Logo Perusahaan --}}
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
