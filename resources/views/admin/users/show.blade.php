<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detail Pengguna" subtitle="{{ $user->name }}">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.edit', $user) }}" variant="white" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </x-ui.btn>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>
            <div class="max-w-3xl mx-auto space-y-6">
                <!-- Profil Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-violet-50">
                        <div class="flex items-center gap-4">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-200">
                            @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-gray-900 text-xl">{{ $user->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                                <div class="mt-1">
                                    <x-ui.status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid gap-4 sm:grid-cols-2 text-sm">
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Role</p>
                                <p class="font-semibold text-gray-900 capitalize">{{ $user->role }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Perusahaan</p>
                                <p class="font-semibold text-gray-900">{{ optional($user->company)->name ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Terdaftar</p>
                                <p class="font-semibold text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email Terverifikasi</p>
                                <p class="font-semibold text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Belum' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
