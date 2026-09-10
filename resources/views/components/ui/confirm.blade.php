{{-- Global Confirm Modal - pengganti confirm() native (127.0.0.1:8000) --}}
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeConfirm(false)"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[slideInDown_0.25s_ease]">
            <div class="p-6">
                <div class="flex gap-4">
                    <div id="confirmIcon" class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 id="confirmTitle" class="text-base font-bold text-slate-900">Konfirmasi</h3>
                        <p id="confirmMessage" class="text-sm text-slate-600 mt-1 leading-relaxed"></p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="closeConfirm(false)" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition">Batal</button>
                <button type="button" onclick="closeConfirm(true)" id="confirmOkBtn" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition">Oke</button>
            </div>
        </div>
    </div>
</div>

<script>
let confirmCallback = null;
let confirmForm = null;

function showConfirm(message, opts = {}) {
    const title = opts.title || 'Konfirmasi';
    const okText = opts.okText || 'Oke';
    const okClass = opts.okClass || 'bg-blue-600 hover:bg-blue-700';
    const icon = opts.icon || 'warning';
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmOkBtn').textContent = okText;
    document.getElementById('confirmOkBtn').className = 'px-5 py-2.5 text-white text-sm font-semibold rounded-xl shadow-sm transition ' + okClass;
    const iconEl = document.getElementById('confirmIcon');
    if (icon === 'danger') {
        iconEl.className = 'w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0';
        iconEl.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
    } else {
        iconEl.className = 'w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0';
        iconEl.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
    }
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirm(confirmed) {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = '';
    if (confirmed && confirmCallback) confirmCallback();
    if (confirmed && confirmForm) confirmForm.submit();
    confirmCallback = null;
    confirmForm = null;
}

// Intercept semua form dengan data-confirm
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form instanceof HTMLFormElement && form.hasAttribute('data-confirm')) {
        if (form.dataset.confirmFired === '1') {
            form.dataset.confirmFired = '0';
            return;
        }
        e.preventDefault();
        const msg = form.getAttribute('data-confirm');
        const title = form.getAttribute('data-confirm-title') || 'Konfirmasi';
        const okText = form.getAttribute('data-confirm-ok') || 'Oke';
        const variant = form.getAttribute('data-confirm-variant') || '';
        const okClass = variant === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700';
        const icon = variant === 'danger' ? 'danger' : 'warning';
        confirmForm = form;
        showConfirm(msg, { title, okText, okClass, icon });
    }
}, true);

// Intercept button/link dengan data-confirm (onclick)
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-confirm-btn]');
    if (btn) {
        e.preventDefault();
        const msg = btn.getAttribute('data-confirm-btn');
        const title = btn.getAttribute('data-confirm-title') || 'Konfirmasi';
        const okText = btn.getAttribute('data-confirm-ok') || 'Oke';
        const href = btn.getAttribute('href');
        const variant = btn.getAttribute('data-confirm-variant') || '';
        const okClass = variant === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700';
        const icon = variant === 'danger' ? 'danger' : 'warning';
        confirmCallback = () => {
            if (href) window.location.href = href;
            else if (btn.form) btn.form.submit();
        };
        showConfirm(msg, { title, okText, okClass, icon });
    }
});

// ESC tutup
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('confirmModal').classList.contains('hidden')) {
        closeConfirm(false);
    }
});
</script>
