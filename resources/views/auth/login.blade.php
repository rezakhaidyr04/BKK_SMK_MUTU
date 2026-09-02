<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Selamat Datang Kembali!</h2>
        <p class="auth-section-subtitle">Masuk untuk mengakses dashboard karir Anda</p>
    </div>
    <div class="auth-card-body">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-input-wrapper">
                <label class="auth-label" for="email">Alamat Email</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="rezahaidyr03@gmail.com"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password">Kata Sandi</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div x-data="{ show: false }" class="relative">
                    <input id="password"
                           :type="show ? 'text' : 'password'"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="ui-input pr-11">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition">
                        <svg x-show="!show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between mb-5">
                <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Ingat saya
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link">Lupa kata sandi?</a>
                @endif
            </div>

            <button type="submit" class="auth-btn">
                Masuk
            </button>
        </form>
    </div>
    <div class="auth-card-footer">
        <p class="auth-footer-text">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link-strong">Daftar sekarang</a>
        </p>
    </div>
</x-guest-layout>