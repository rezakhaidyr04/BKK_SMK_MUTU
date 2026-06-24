<x-guest-layout>
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali!</h2>
        <p class="text-sm text-slate-500 mt-1">Masuk untuk mengakses dasbor karir Anda</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="ui-label">Alamat Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="email@contoh.com"
                   class="ui-input">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="ui-label mb-0">Kata Sandi</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
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
                   class="ui-input">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="remember_me" class="text-sm text-slate-600 cursor-pointer">Ingat saya</label>
        </div>

        <x-ui.btn type="submit" class="w-full">Masuk</x-ui.btn>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Daftar sekarang</a>
    </p>
</x-guest-layout>
