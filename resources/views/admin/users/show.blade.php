<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengguna</h2>
                <p class="text-sm text-gray-500">Informasi lengkap akun pengguna.</p>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Ubah</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Profil</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Peran:</strong> {{ \App\Support\Label::role($user->role) }}</p>
                            <p><strong>Status:</strong> {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                            <p><strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Hubungan</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Perusahaan:</strong> {{ optional($user->company)->name ?? '-' }}</p>
                            <p><strong>Aplikasi:</strong> {{ $user->applications->count() }}</p>
                            <p><strong>Sertifikat:</strong> {{ $user->certificates->count() }}</p>
                            <p><strong>CV Tersimpan:</strong> {{ $user->cvFiles->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aplikasi Terakhir</h3>
                    <div class="space-y-3">
                        @forelse($user->applications->take(5) as $application)
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $application->job->title ?? 'Lowongan tidak tersedia' }}</p>
                            <p class="text-sm text-gray-600">{{ \App\Support\Label::applicationStatus($application->status) }} - {{ $application->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada aplikasi.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Berkas</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        @forelse($user->cvFiles as $cv)
                        <p>{{ $cv->filename ?? 'CV' }} • {{ $cv->created_at->format('d M Y') }}</p>
                        @empty
                        <p>Tidak ada CV tersimpan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
