{{-- Form upload foto profil — dipakai bersama oleh halaman Profil (umum) & Pengaturan Admin.
     Action: route('profile.avatar.update') (POST /profile/avatar). --}}
@php
    $avatarPreviewExists = $user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar);
    $avatarPreviewUrl = $avatarPreviewExists ? asset('storage/' . ltrim($user->avatar, '/')) : null;
    $avatarInitial = strtoupper(substr($user->name, 0, 1));
@endphp

<form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="contents">
    @csrf
    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 text-center">
        @if($avatarPreviewUrl)
            <img src="{{ $avatarPreviewUrl }}" alt="Foto profil" class="mx-auto h-24 w-24 rounded-2xl object-cover ring-1 ring-slate-200"
                 onerror="this.style.display='none';document.getElementById('avatar-fallback-initial').style.display='flex'">
            <div id="avatar-fallback-initial" style="display:none" class="mx-auto h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-3xl font-extrabold text-white">{{ $avatarInitial }}</div>
        @else
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-3xl font-extrabold text-white">{{ $avatarInitial }}</div>
        @endif

        <label for="avatar" class="mt-3 inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-700">
            Pilih Foto Baru
        </label>
        <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/jpeg,image/png,image/webp"
               onchange="previewAvatar(event)">
        <button type="submit" class="mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            Simpan Foto
        </button>
        <p class="mt-2 text-[11px] leading-relaxed text-slate-400">JPG, PNG, WebP · Maksimal 3MB.<br>Pilih foto lalu klik <b>Simpan Foto</b> (tombol <b>Simpan Perubahan</b> di bawah tidak ikut menyimpan foto).</p>

        @if (session('status') === 'avatar-updated')
            <p class="mt-2 text-xs font-semibold text-green-600">Foto profil tersimpan.</p>
        @endif

        @if($avatarPreviewUrl)
            <img id="avatar-preview" src="{{ $avatarPreviewUrl }}" alt="Pratinjau foto" class="hidden mx-auto mt-3 h-24 w-24 rounded-full object-cover ring-2 ring-blue-200">
        @else
            <div id="avatar-preview-placeholder" class="hidden mx-auto mt-3 h-24 w-24 items-center justify-center rounded-full bg-slate-200 text-xl font-bold text-slate-500">{{ $avatarInitial }}</div>
            <img id="avatar-preview" src="" alt="Pratinjau foto" class="hidden mx-auto mt-3 h-24 w-24 rounded-full object-cover ring-2 ring-blue-200">
        @endif

        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
    </div>
</form>

{{-- Cropper Modal --}}
<div id="cropperModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Sesuaikan Foto Profil</h3>
        <div class="max-h-[60vh] overflow-hidden rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200">
            <img id="imageToCrop" src="" class="max-w-full hidden">
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="closeCropper()" class="px-5 py-2.5 text-slate-600 font-medium hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">Batal</button>
            <button type="button" onclick="applyCrop()" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl shadow-sm hover:bg-blue-700 hover:shadow transition">Simpan Potongan</button>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper = null;

function previewAvatar(event) {
    const input = event.target;
    const file = input.files[0];
    if (!file) return;
    // Kalau CropperJS gagal dimuat (offline/CDN), tetap pakai file asli + preview langsung.
    if (typeof Cropper === 'undefined') {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgPreview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-preview-placeholder');
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        const modal = document.getElementById('cropperModal');
        const img = document.getElementById('imageToCrop');
        img.src = e.target.result;
        img.classList.remove('hidden');
        modal.classList.remove('hidden');

        if (cropper) {
            cropper.destroy();
        }
        cropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.85,
            cropBoxMovable: true,
            cropBoxResizable: true,
            guides: true,
            center: true,
            highlight: false,
            zoomable: true,
            scalable: true,
            background: false,
        });
    };
    reader.readAsDataURL(file);
    // JANGAN reset input di sini — file asli dipertahankan supaya tetap
    // terupload kalau user langsung klik Simpan tanpa crop.
}

function closeCropper() {
    document.getElementById('cropperModal').classList.add('hidden');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function applyCrop() {
    if (!cropper) return;
    cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob((blob) => {
        const file = new File([blob], 'avatar_cropped.webp', { type: 'image/webp' });

        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('avatar').files = dt.files;

        const reader = new FileReader();
        reader.onload = function(e) {
            const imgPreview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-preview-placeholder');
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);

        closeCropper();
    }, 'image/webp');
}
</script>
@endpush
