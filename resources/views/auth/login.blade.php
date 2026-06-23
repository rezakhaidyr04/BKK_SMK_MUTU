<x-guest-layout>
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali!</h2>
        <p class="text-sm text-gray-500 mt-1">Masuk untuk mengakses dasbor karir Anda</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Alamat Email
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="email@contoh.com"
                   class="block w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Kata Sandi
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                    Lupa kata sandi?
                </a>
                @endif
            </div>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="block w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-2">
            <input id="remember_me"
                   type="checkbox"
                   name="remember"
                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
            <label for="remember_me" class="text-sm text-gray-600 cursor-pointer">
                Ingat saya
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
            Masuk
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
            Daftar sekarang
        </a>
    </p>
</x-guest-layout>
