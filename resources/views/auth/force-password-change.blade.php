<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Ganti Password Pertama</h2>
        <p class="auth-section-subtitle">Demi keamanan, Anda diwajibkan mengganti password sementara yang diberikan oleh admin saat login pertama kali.</p>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('password.force-update') }}">
            @csrf

            <div class="auth-input-wrapper">
                <label class="auth-label" for="current_password">Password Sementara Saat Ini</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="current_password"
                       type="password"
                       name="current_password"
                       required
                       autocomplete="current-password"
                       placeholder="Masukkan password sementara"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password">Password Baru</label>
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
                <label class="auth-label" for="password_confirmation">Konfirmasi Password Baru</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi password baru"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

            <button type="submit" class="auth-btn">
                Simpan Password & Lanjutkan
            </button>
        </form>
    </div>
    <div class="auth-card-footer">
        <p class="auth-footer-text">
            <a href="{{ route('logout') }}" class="auth-link-strong">Keluar</a>
        </p>
    </div>
</x-guest-layout>