<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Buat Pengguna Baru" subtitle="Tambahkan akun pengguna baru ke sistem.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.users.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-medium">
        <form method="POST" action="{{ route('admin.users.store') }}" class="ui-form-stack">
            @csrf

            <div>
                <label for="name" class="ui-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="ui-input">
            </div>

            <div>
                <label for="email" class="ui-label">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="ui-input">
            </div>

            <div>
                <label for="role" class="ui-label">Peran</label>
                <select id="role" name="role" required class="ui-select">
                    <option value="">-- Pilih Peran --</option>
                    <option value="student"  {{ old('role') === 'student'  ? 'selected' : '' }}>Siswa</option>
                    <option value="alumni"   {{ old('role') === 'alumni'   ? 'selected' : '' }}>Alumni</option>
                    <option value="company"  {{ old('role') === 'company'  ? 'selected' : '' }}>Perusahaan</option>
                    <option value="teacher"  {{ old('role') === 'teacher'  ? 'selected' : '' }}>Guru</option>
                    <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div>
                <label for="password" class="ui-label">Kata Sandi</label>
                <input type="password" id="password" name="password" required minlength="8" class="ui-input">
                <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
            </div>

            <div>
                <label for="password_confirmation" class="ui-label">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="ui-input">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_active" class="text-sm text-slate-700">Aktifkan akun langsung</label>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Buat Pengguna</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.users.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
