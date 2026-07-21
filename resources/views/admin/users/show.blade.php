<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Detail Pengguna</h1>
                        <p class="text-purple-100">{{ $user->name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-5 py-2.5 border border-white/30 text-white text-sm font-semibold rounded-xl hover:bg-white/10 transition">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <div class="max-w-3xl mx-auto space-y-6">
                <!-- Profil Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center gap-4">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-indigo-200">
                            @else
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
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
    </div>
</x-app-layout>
