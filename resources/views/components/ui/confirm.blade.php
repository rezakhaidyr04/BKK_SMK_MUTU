{{-- Global Confirm Modal Premium --}}
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-[#0a1633]/70 backdrop-blur-[2px]" onclick="closeConfirm(false)"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-[0_20px_60px_rgba(10,22,51,0.35)] w-full max-w-[440px] overflow-hidden border border-slate-100 animate-[scaleIn_0.22s_cubic-bezier(0.34,1.56,0.64,1)]">
            {{-- Header accent --}}
            <div class="h-1 w-full bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-600"></div>
            <div class="p-7 pb-5">
                <div class="flex justify-between items-start gap-4">
                    <div id="confirmIconWrap" class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-sm">
                        <svg id="confirmIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>
                    <button onclick="closeConfirm(false)" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-700 transition flex-shrink-0" aria-label="Tutup">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <h3 id="confirmTitle" class="text-lg font-bold text-slate-900 mt-4">Konfirmasi</h3>
                <p id="confirmMessage" class="text-sm text-slate-500 mt-1.5 leading-relaxed"></p>
            </div>
            <div class="px-7 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                <button type="button" onclick="closeConfirm(false)" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition shadow-sm">Batal</button>
                <button type="button" onclick="closeConfirm(true)" id="confirmOkBtn" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/25 hover:shadow-blue-600/30 hover:-translate-y-px active:translate-y-0 transition-all">Oke</button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn { from { opacity:0; transform:scale(0.96) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
</style>

<script>
let confirmCallback = null;
let confirmForm = null;

function showConfirm(message, opts = {}) {
    const title = opts.title || 'Konfirmasi';
    const okText = opts.okText || 'Oke';
    const variant = opts.variant || opts.icon || 'warning';
    const okClassBase = 'px-6 py-2.5 text-white text-sm font-bold rounded-xl shadow-lg transition-all hover:-translate-y-px active:translate-y-0 ';
    let okClass = variant === 'danger'
        ? okClassBase + 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-red-500/25'
        : okClassBase + 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-blue-500/25';
    if (opts.okClass) okClass = 'px-6 py-2.5 text-white text-sm font-bold rounded-xl shadow-lg transition-all ' + opts.okClass;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    const btn = document.getElementById('confirmOkBtn');
    btn.textContent = okText;
    btn.className = okClass;
    const wrap = document.getElementById('confirmIconWrap');
    const icon = document.getElementById('confirmIcon');
    if (variant === 'danger') {
        wrap.className = 'w-12 h-12 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 flex-shrink-0 shadow-sm';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>';
    } else if (variant === 'info') {
        wrap.className = 'w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 shadow-sm';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    } else {
        wrap.className = 'w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0 shadow-sm';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>';
    }
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirm(confirmed) {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = '';
    if (confirmed && confirmCallback) confirmCallback();
    if (confirmed && confirmForm) {
        confirmForm.dataset.confirmFired = '1';
        confirmForm.submit();
    }
    confirmCallback = null;
    confirmForm = null;
}

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
        const icon = variant === 'danger' ? 'danger' : 'warning';
        confirmForm = form;
        showConfirm(msg, { title, okText, variant: icon });
    }
}, true);

document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-confirm-btn]');
    if (btn) {
        e.preventDefault();
        const msg = btn.getAttribute('data-confirm-btn');
        const title = btn.getAttribute('data-confirm-title') || 'Konfirmasi';
        const okText = btn.getAttribute('data-confirm-ok') || 'Oke';
        const href = btn.getAttribute('href');
        const variant = btn.getAttribute('data-confirm-variant') || '';
        const icon = variant === 'danger' ? 'danger' : 'warning';
        confirmCallback = () => {
            if (href) window.location.href = href;
            else if (btn.form) btn.form.submit();
        };
        showConfirm(msg, { title, okText, variant: icon });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('confirmModal').classList.contains('hidden')) {
        closeConfirm(false);
    }
});
</script>
