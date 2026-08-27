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
