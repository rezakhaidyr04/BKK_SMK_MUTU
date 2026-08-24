<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Pengguna" subtitle="Perbarui data dan kontrol akses pengguna.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    @if($errors->any())
    <x-ui.alert type="danger" class="mb-6 max-w-xl mx-auto">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </x-ui.alert>
    @endif

    <div class="max-w-xl mx-auto">
        <x-ui.panel>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="ui-label">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="ui-select">
                        <option value="admin"   {{ old('role', $user->role) == 'admin'   ? 'selected' : '' }}>Admin</option>
                        <option value="company" {{ old('role', $user->role) == 'company' ? 'selected' : '' }}>Perusahaan</option>
                        <option value="jobseeker" {{ old('role', $user->role) == 'jobseeker' ? 'selected' : '' }}>Pencari Kerja</option>
                        <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Guru</option>
                    </select>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="ui-label">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti" class="ui-input">
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label class="text-sm text-slate-700">Aktifkan akun</label>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <x-ui.btn href="{{ route('admin.users.index') }}" variant="secondary">Batal</x-ui.btn>
                    <x-ui.btn type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </x-ui.btn>
                </div>
            </form>
        </x-ui.panel>
    </div>
</x-app-layout>
