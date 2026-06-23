<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Header card dengan stats --}}
            <div class="p-6 sm:p-8 bg-white/80 backdrop-blur-xl shadow sm:rounded-2xl border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                 class="w-14 h-14 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->applications()->count() }}</div>
                            <div class="text-xs text-gray-500">Lamaran</div>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->bookmarks()->count() }}</div>
                            <div class="text-xs text-gray-500">Disimpan</div>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="text-lg font-bold text-gray-900">{{ $user->certificates()->count() }}</div>
                            <div class="text-xs text-gray-500">Sertifikat</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                {{-- Kolom kiri: form info profil (lebih lebar) --}}
                <div class="lg:col-span-3 space-y-6">
                    <div class="p-6 bg-white shadow sm:rounded-2xl border border-gray-100">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                    <div class="p-6 bg-white shadow sm:rounded-2xl border border-gray-100">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Kolom kanan: hapus akun --}}
                <div class="lg:col-span-2">
                    <div class="p-6 bg-white shadow sm:rounded-2xl border border-gray-100">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
