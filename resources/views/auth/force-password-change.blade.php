<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Demi keamanan, Anda diwajibkan untuk mengganti password sementara yang diberikan oleh admin saat login pertama kali.') }}
    </div>

    <form method="POST" action="{{ route('password.force-update') }}">
        @csrf

        <!-- Current Password -->
        <div>
            <x-input-label for="current_password" value="{{ __('Password Sementara (Saat Ini)') }}" />
            <x-text-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password Baru') }}" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="{{ __('Konfirmasi Password Baru') }}" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- Logout option for the user in case they want to back out -->
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                {{ __('Keluar (Logout)') }}
            </a>

            <x-primary-button>
                {{ __('Simpan Password & Lanjutkan') }}
            </x-primary-button>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</x-guest-layout>
