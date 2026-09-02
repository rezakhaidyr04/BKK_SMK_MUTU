<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Konfirmasi Kata Sandi</h2>
        <p class="auth-section-subtitle">Ini adalah area aman. Konfirmasi kata sandi Anda sebelum melanjutkan.</p>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="auth-input-wrapper">
                <label class="auth-label" for="password">Kata Sandi</label>
                <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <button type="submit" class="auth-btn">
                Konfirmasi
            </button>
        </form>
    </div>
</x-guest-layout>