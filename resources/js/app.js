import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Kunci tombol submit saat form dikirim untuk mencegah double-submit.
// Form yang mengelola statusnya sendiri (AJAX) bisa menandai diri dengan data-no-lock.
(function () {
    const style = document.createElement('style');
    style.textContent =
        '.btn-spinner{display:inline-block;width:1em;height:1em;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;margin-right:.5rem;animation:btn-spin .6s linear infinite;vertical-align:-2px}' +
        '@keyframes btn-spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(style);

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-lock')) {
            return;
        }

        const buttons = [...form.querySelectorAll('button[type="submit"]')];
        if (buttons.length === 0) return;

        // Defer agar handler lain (mis. konfirmasi/sinkronisasi editor) jalan dulu.
        setTimeout(() => {
            buttons.forEach((btn) => {
                if (btn.disabled) return;
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.dataset.loading = '1';
                btn.insertAdjacentHTML(
                    'afterbegin',
                    '<span class="btn-spinner" aria-hidden="true"></span>'
                );
            });
        }, 0);
    });
})();

Alpine.start();

// A/B testing: teruskan event yang di-dispatch dari Alpine ke endpoint tracking.
function trackAbTest(eventName, variant) {
    if (!eventName) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('/ab-test/track', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ event: eventName, variant: variant ?? null }),
    }).catch(() => {});
}

document.addEventListener('ab-test-track', (e) => {
    const detail = e.detail || {};
    trackAbTest(detail.event, detail.variant);
});

// Lacak impression (variasi yang benar-benar ditampilkan) sekali saat halaman dimuat.
document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('[data-ab-variant]');
    if (el) {
        trackAbTest('impression', el.dataset.abVariant);
    }
});
