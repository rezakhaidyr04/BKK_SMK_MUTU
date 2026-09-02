<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Daftar sebagai Pencari Kerja</h2>
        <p class="auth-section-subtitle">Buat akun untuk mencari lowongan, membuat CV, dan melamar pekerjaan.</p>
    </div>
    <div class="auth-card-body">
        @if($errors->any())
            <div class="auth-alert auth-alert-error mb-4">
                <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="auth-input-wrapper">
                <label class="auth-label" for="name">Nama Lengkap</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       autocomplete="name"
                       placeholder="Nama lengkap Anda"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

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
                       autocomplete="username"
                       placeholder="email@contoh.com"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password">Kata Sandi</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi kata sandi"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

            <button type="submit" class="auth-btn mt-2">
                Buat Akun
            </button>
        </form>
    </div>
    <div class="auth-card-footer">
        <p class="auth-footer-text">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="auth-link-strong">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>