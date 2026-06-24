<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detail Pengguna" subtitle="Informasi lengkap akun pengguna.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.edit', $user) }}" size="sm">Ubah</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.panel title="Profil" class="mb-6">
        <div class="grid gap-6 md:grid-cols-2 text-sm">
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Nama:</span> {{ $user->name }}</p>
                <p><span class="font-semibold text-slate-700">Email:</span> {{ $user->email }}</p>
                <p><span class="font-semibold text-slate-700">Role:</span> <span class="capitalize">{{ $user->role }}</span></p>
            </div>
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Status:</span>
                    <x-ui.status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                </p>
                <p><span class="font-semibold text-slate-700">Perusahaan:</span> {{ optional($user->company)->name ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Terdaftar:</span> {{ $user->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </x-ui.panel>
</x-app-layout>
