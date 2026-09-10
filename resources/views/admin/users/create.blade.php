<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Buat Pengguna Baru" subtitle="Tambahkan akun pengguna baru ke sistem." eyebrow="Admin › Pengguna">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Buat Akun Baru
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    @if($errors->any())
    <x-ui.alert type="danger" class="mb-6 max-w-xl mx-auto">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-ui.alert>
    @endif

    <div class="max-w-xl mx-auto">
        <x-ui.panel>
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="ui-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Peran <span class="text-red-500">*</span></label>
                    <select id="role" name="role" required class="ui-select">
                        <option value="">-- Pilih Peran --</option>
                        <option value="umum" {{ old('role') === 'umum' ? 'selected' : '' }}>Pengguna Umum</option>
                        <option value="company" {{ old('role') === 'company' ? 'selected' : '' }}>Perusahaan</option>
                        <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="ui-label">Kata Sandi <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required minlength="8" class="ui-input">
                    <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
                </div>
                <div>
                    <label class="ui-label">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="ui-input">
                </div>
                <div class="flex items-center gap-2 py-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm text-slate-700">Aktifkan akun langsung</label>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <x-ui.btn href="{{ route('admin.users.index') }}" variant="secondary">Batal</x-ui.btn>
                    <x-ui.btn type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Buat Pengguna
                    </x-ui.btn>
                </div>
            </form>
        </x-ui.panel>
    </div>
        </div>
    </div>
</x-app-layout>
