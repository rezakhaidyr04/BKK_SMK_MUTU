<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Buat Kata Sandi Baru</h2>
        <p class="auth-section-subtitle">Masukkan email dan password baru Anda untuk melanjutkan.</p>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="auth-input-wrapper">
                <label class="auth-label" for="email">Alamat Email</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email', $request->email) }}"
                       required
                       autofocus
                       autocomplete="username"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password">Kata Sandi Baru</label>
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
                Atur Ulang Kata Sandi
            </button>
        </form>
    </div>
</x-guest-layout>