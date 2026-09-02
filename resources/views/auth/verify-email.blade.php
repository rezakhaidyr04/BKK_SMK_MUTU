<x-guest-layout>
    <div class="auth-card-icon-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <div class="auth-card-header">
        <h2 class="auth-section-title">Verifikasi Email</h2>
        <p class="auth-section-subtitle">Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik tautan yang kami kirim.</p>
    </div>
    <div class="auth-card-body">
        @if(session('status') == 'verification-link-sent')
            <div class="auth-alert auth-alert-success mb-3">
                Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
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
                       autocomplete="username"
                       placeholder="email@contoh.com"
                       class="ui-input">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <button type="submit" class="auth-btn">
                Kirim Ulang Email Verifikasi
            </button>
        </form>
    </div>
    <div class="auth-card-footer">
        <p class="auth-footer-text">
            Sudah diverifikasi?
            <a href="{{ route('home') }}" class="auth-link-strong">Beranda</a>
        </p>
    </div>
</x-guest-layout>