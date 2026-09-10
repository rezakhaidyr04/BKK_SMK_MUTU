{{-- Global Confirm Modal - sesuai referensi email broadcast --}}
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-[#0a1a3a]/60 backdrop-blur-sm" onclick="closeConfirm(false)"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full overflow-hidden animate-[scaleIn_0.22s_cubic-bezier(0.34,1.56,0.64,1)] relative my-4" style="max-width:560px; border-radius:20px; box-shadow:0 20px 48px rgba(10,22,51,0.3);">
            {{-- Close X --}}
            <button onclick="closeConfirm(false)" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="flex flex-col md:flex-row">
                {{-- Kiri: Ilustrasi --}}
                <div class="md:w-1/3 bg-gradient-to-br from-blue-50 via-white to-blue-100/60 p-5 flex items-center justify-center relative overflow-hidden min-h-[180px] md:min-h-[280px]">
                    {{-- Dekor blob atas --}}
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-blue-200/40 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-blue-100/60 rounded-full"></div>
                    {{-- Ilustrasi amplop --}}
                    <div class="relative">
                        <svg viewBox="0 0 200 180" class="w-36 h-32 drop-shadow-lg">
                            {{-- Glow bulat belakang --}}
                            <ellipse cx="100" cy="105" rx="75" ry="65" fill="#dbeafe" opacity="0.45"/>
                            <ellipse cx="100" cy="105" rx="55" ry="48" fill="#eff6ff" opacity="0.7"/>
                            {{-- Pesawat kertas atas --}}
                            <g transform="translate(18,28)">
                                <path d="M0 12 L32 0 L28 14 L12 18 Z" fill="#60a5fa" stroke="#3b82f6" stroke-width="0.8" stroke-linejoin="round"/>
                                <path d="M12 18 L28 14 L18 22 Z" fill="#93c5fd"/>
                            </g>
                            {{-- Garis putus pesawat --}}
                            <path d="M42 52 Q 28 72 52 92" fill="none" stroke="#60a5fa" stroke-width="1.6" stroke-dasharray="4 5" stroke-linecap="round" opacity="0.9"/>
                            {{-- Petir kecil --}}
                            <g transform="translate(138,38)">
                                <path d="M0 6 L6 0 L4 5 L8 6 L2 12 L4 7 Z" fill="#60a5fa" stroke="#3b82f6" stroke-width="0.6" stroke-linejoin="round"/>
                            </g>
                            <circle cx="155" cy="52" r="2.5" fill="#60a5fa" opacity="0.6"/>
                            {{-- Amplop --}}
                            <g transform="translate(0,12)">
                                <rect x="42" y="72" width="116" height="78" rx="8" fill="#2563eb" stroke="#1d4ed8" stroke-width="1.2"/>
                                <path d="M42 72 L100 115 L158 72" fill="#1e40af" opacity="0.95"/>
                                <path d="M42 150 L100 108 L158 150" fill="#3b82f6" stroke="#2563eb" stroke-width="1"/>
                                {{-- Surat --}}
                                <rect x="62" y="52" width="76" height="62" rx="6" fill="white" stroke="#dbeafe" stroke-width="1.2"/>
                                <rect x="72" y="68" width="56" height="5" rx="2.5" fill="#93c5fd"/>
                                <rect x="72" y="78" width="42" height="4" rx="2" fill="#bfdbfe"/>
                                <rect x="72" y="86" width="48" height="4" rx="2" fill="#bfdbfe"/>
                                {{-- Badge 1 --}}
                                <circle cx="148" cy="58" r="16" fill="#f43f5e" stroke="white" stroke-width="2.5"/>
                                <text x="148" y="63" text-anchor="middle" fill="white" font-size="13" font-weight="800" font-family="Inter,sans-serif">1</text>
                            </g>
                            {{-- Sparkle --}}
                            <path d="M28 128 L30 134 L36 136 L30 138 L28 144 L26 138 L20 136 L26 134 Z" fill="#60a5fa"/>
                            <path d="M162 138 L164 142 L168 144 L164 146 L162 150 L160 146 L156 144 L160 142 Z" fill="#60a5fa"/>
                        </svg>
                    </div>
                </div>

                {{-- Kanan: Konten --}}
                <div class="flex-1 p-5 md:p-6 flex flex-col">
                    <div class="flex items-center gap-2 text-blue-600">
                        <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        </div>
                        <span class="w-px h-4 bg-blue-200"></span>
                        <span id="confirmEyebrow" class="text-xs font-bold tracking-widest uppercase text-blue-600">KONFIRMASI PENGIRIMAN EMAIL</span>
                    </div>

                    <h3 id="confirmTitle" class="text-xl md:text-2xl font-bold text-[#0a1a3a] mt-3 leading-tight">Kirim Notifikasi Lowongan ke Semua Pencari Kerja?</h3>
                    <p id="confirmMessage" class="text-sm text-slate-500 mt-2.5 leading-relaxed">Yakin ingin membroadcast notifikasi lowongan ini ke semua pencari kerja melalui email?</p>

                    <div id="confirmInfoBox" class="mt-5 bg-blue-50/70 border border-blue-100 rounded-2xl p-3.5 flex gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 9a6 6 0 00-3-1 6 6 0 00-3 1 3 3 0 00-3 3v1h12v-1a3 3 0 00-3-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM7 10a3 3 0 013-3 3 3 0 013 3v1H7v-1z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-500">Akan dikirim ke</p>
                            <p id="confirmInfoMain" class="text-sm font-bold text-slate-900">Seluruh pencari kerja</p>
                            <p class="text-xs text-slate-400 truncate">Seluruh pencari kerja yang terdaftar di sistem</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-6 flex gap-3 justify-end">
                        <button type="button" onclick="closeConfirm(false)" class="px-7 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-semibold rounded-full hover:bg-slate-50 hover:border-slate-300 transition">Batal</button>
                        <button type="button" onclick="closeConfirm(true)" id="confirmOkBtn" class="px-7 py-2.5 bg-gradient-to-b from-blue-500 to-blue-700 text-white text-sm font-bold rounded-full shadow-[0_8px_20px_rgba(37,99,235,0.35)] hover:from-blue-600 hover:to-blue-800 hover:shadow-[0_10px_24px_rgba(37,99,235,0.4)] hover:-translate-y-px active:translate-y-0 transition-all inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            Oke
                        </button>
                    </div>
                </div>
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
    const title = opts.title || 'Kirim Notifikasi Lowongan ke Semua Pencari Kerja?';
    const eyebrow = opts.eyebrow || 'KONFIRMASI PENGIRIMAN EMAIL';
    const okText = opts.okText || 'Oke';
    const variant = opts.variant || opts.icon || 'warning';
    const infoMain = opts.infoMain || 'Seluruh pencari kerja';
    const infoSub = opts.infoSub || 'Seluruh pencari kerja yang terdaftar di sistem';
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmEyebrow').textContent = eyebrow;
    document.getElementById('confirmInfoMain').textContent = infoMain;
    document.getElementById('confirmInfoBox').querySelector('p:last-child').textContent = infoSub;
    const btn = document.getElementById('confirmOkBtn');
    btn.childNodes[btn.childNodes.length-1].textContent = ' ' + okText;
    // variant colors
    if (variant === 'danger') {
        btn.className = 'px-7 py-2.5 bg-gradient-to-b from-red-500 to-red-700 text-white text-sm font-bold rounded-full shadow-[0_8px_20px_rgba(220,38,38,0.35)] hover:from-red-600 hover:to-red-800 transition-all inline-flex items-center gap-2';
        document.getElementById('confirmEyebrow').textContent = opts.eyebrow || 'KONFIRMASI HAPUS';
    } else {
        btn.className = 'px-7 py-2.5 bg-gradient-to-b from-blue-500 to-blue-700 text-white text-sm font-bold rounded-full shadow-[0_8px_20px_rgba(37,99,235,0.35)] hover:from-blue-600 hover:to-blue-800 hover:shadow-[0_10px_24px_rgba(37,99,235,0.4)] hover:-translate-y-px active:translate-y-0 transition-all inline-flex items-center gap-2';
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
        const title = form.getAttribute('data-confirm-title') || 'Kirim Notifikasi Lowongan ke Semua Pencari Kerja?';
        const eyebrow = form.getAttribute('data-confirm-eyebrow') || 'KONFIRMASI PENGIRIMAN EMAIL';
        const okText = form.getAttribute('data-confirm-ok') || 'Oke';
        const variant = form.getAttribute('data-confirm-variant') || '';
        const icon = variant === 'danger' ? 'danger' : 'warning';
        confirmForm = form;
        showConfirm(msg, { title, eyebrow, okText, variant: icon });
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
