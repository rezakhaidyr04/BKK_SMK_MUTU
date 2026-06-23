<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white/80 backdrop-blur-xl shadow sm:rounded-2xl border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Pengaturan Profil
                        </div>
                        <h2 class="mt-3 text-2xl font-bold text-gray-900">Perbarui informasi & keamanan akun</h2>
                        <p class="mt-1 text-sm text-gray-600">Kelola profil, kata sandi, dan hapus akun kapan saja.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-3 w-full sm:w-auto">
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->applications()->count() ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Lamaran</div>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->bookmarks()->count() ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Disimpan</div>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->certificates()->count() ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Sertifikat</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 space-y-6">
                    <div class="p-4 sm:p-6 bg-white shadow sm:rounded-2xl border border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 bg-white shadow sm:rounded-2xl border border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-2xl border border-gray-100">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
