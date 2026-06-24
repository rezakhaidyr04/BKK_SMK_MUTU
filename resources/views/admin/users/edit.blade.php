<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Pengguna" subtitle="Perbarui data dan kontrol akses pengguna.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.users.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-medium">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div>
                <label class="ui-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="ui-input">
            </div>
            <div>
                <label class="ui-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="ui-input">
            </div>
            <div>
                <label class="ui-label">Role</label>
                <select name="role" required class="ui-select">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="company" {{ old('role', $user->role) == 'company' ? 'selected' : '' }}>Perusahaan</option>
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="alumni" {{ old('role', $user->role) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="ui-label">Password Baru</label>
                    <input type="password" name="password" class="ui-input" placeholder="Kosongkan jika tidak ingin mengganti">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        Aktifkan akun
                    </label>
                </div>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Simpan</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.users.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
