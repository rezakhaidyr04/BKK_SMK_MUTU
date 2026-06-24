<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Daftar Pengguna" subtitle="Kelola semua akun pengguna di sistem.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.create') }}" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambah Pengguna
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-4 md:grid-cols-4 w-full">
            <div class="ui-filter-field">
                <label class="ui-label">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Nama, email, role" />
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Role</label>
                <select name="role" class="ui-select">
                    <option value="">Semua</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="company" {{ request('role') == 'company' ? 'selected' : '' }}>Perusahaan</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="alumni" {{ request('role') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.users.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Perusahaan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="capitalize">{{ $user->role }}</td>
                        <td>{{ optional($user->company)->name ?? '-' }}</td>
                        <td>
                            <x-ui.status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                        </td>
                        <td>
                            <div class="ui-table-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800">Ubah</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <x-ui.empty-state
                                title="Tidak ada pengguna ditemukan"
                                description="Coba ubah filter pencarian atau tambahkan pengguna baru."
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </x-ui.panel>
</x-app-layout>
