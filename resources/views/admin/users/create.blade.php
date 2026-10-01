<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Buat Pengguna Baru" subtitle="Tambahkan akun pengguna baru ke sistem." eyebrow="Admin › Pengguna">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Buat Akun Baru
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            @if($errors->any())
            <x-ui.alert type="danger" class="mb-6 max-w-4xl mx-auto">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </x-ui.alert>
            @endif

            <div class="user-form-layout" data-reveal>
                {{-- ===== Sidebar preview ===== --}}
                <aside class="user-form-side">
                    <div class="user-preview-card">
                        <div class="user-preview-ava" id="previewAva">?</div>
                        <h4 id="previewName">Nama Pengguna</h4>
                        <p id="previewEmail">email@contoh.com</p>
                        <span class="role-badge role-umum" id="previewRole">👤 Umum</span>
                        <div class="user-preview-div"></div>
                        <ul class="user-tips">
                            <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Gunakan email aktif & valid</li>
                            <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Password minimal 8 karakter</li>
                            <li><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Role menentukan hak akses</li>
                        </ul>
                    </div>
                    <div class="user-steps">
                        <div class="user-step active"><span>1</span> Informasi Akun</div>
                        <div class="user-step"><span>2</span> Peran & Akses</div>
                        <div class="user-step"><span>3</span> Keamanan</div>
                    </div>
                </aside>

                {{-- ===== Form card ===== --}}
                <div class="user-form-card">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm" class="user-form">
                        @csrf

                        {{-- Section 1 --}}
                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <h3>Informasi Akun</h3>
                                    <p>Nama lengkap & email untuk login</p>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="ui-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="cth: Budi Santoso" class="ui-input has-icon" autocomplete="name">
                                    </div>
                                    @error('name')<p class="ui-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="ui-label" for="email">Alamat Email <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="cth: budi@email.com" class="ui-input has-icon" autocomplete="email">
                                    </div>
                                    @error('email')<p class="ui-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2 --}}
                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon violet">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.6 2A9 9 0 1112 3a9 9 0 015.6 17z"/></svg>
                                </div>
                                <div>
                                    <h3>Peran & Akses <span class="text-red-500">*</span></h3>
                                    <p>Pilih hak akses yang sesuai</p>
                                </div>
                            </div>
                            @php $oldRole = old('role', 'umum'); @endphp
                            <div class="role-cards">
                                <label class="role-card {{ $oldRole==='umum' ? 'selected' : '' }}">
                                    <input type="radio" name="role" value="umum" {{ $oldRole==='umum' ? 'checked' : '' }} class="sr-only">
                                    <div class="role-card-icon gray">👤</div>
                                    <div><strong>Pengguna Umum</strong><small>Pencari kerja / alumni</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                                <label class="role-card {{ $oldRole==='company' ? 'selected' : '' }}">
                                    <input type="radio" name="role" value="company" {{ $oldRole==='company' ? 'checked' : '' }} class="sr-only">
                                    <div class="role-card-icon blue">🏢</div>
                                    <div><strong>Perusahaan</strong><small>Posting lowongan kerja</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                                <label class="role-card {{ $oldRole==='admin' ? 'selected' : '' }}">
                                    <input type="radio" name="role" value="admin" {{ $oldRole==='admin' ? 'checked' : '' }} class="sr-only">
                                    <div class="role-card-icon purple">🛡️</div>
                                    <div><strong>Admin</strong><small>Akses penuh sistem</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                            </div>
                            @error('role')<p class="ui-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Section 3 --}}
                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon emerald">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <div>
                                    <h3>Keamanan</h3>
                                    <p>Password untuk login pertama</p>
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="ui-label" for="password">Kata Sandi <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        <input type="password" id="password" name="password" required minlength="8" placeholder="Min. 8 karakter" class="ui-input has-icon has-eye">
                                        <button type="button" class="eye-btn" data-toggle="password" title="Tampilkan" aria-label="Tampilkan sandi">
                                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg class="eye-shut w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                        </button>
                                    </div>
                                    <div class="pw-meter"><i id="pwBar"></i></div>
                                    <p class="form-hint" id="pwHint">Gunakan kombinasi huruf, angka & simbol.</p>
                                    @error('password')<p class="ui-error">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="ui-label" for="password_confirmation">Konfirmasi <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi sandi" class="ui-input has-icon has-eye">
                                        <button type="button" class="eye-btn" data-toggle="password_confirmation" title="Tampilkan" aria-label="Tampilkan sandi">
                                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg class="eye-shut w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                        </button>
                                    </div>
                                    <p class="form-hint" id="matchHint">Pastikan kedua sandi sama.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="status-row">
                            <label class="switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <strong>Aktifkan akun langsung</strong>
                                <p>Pengguna bisa langsung login setelah dibuat</p>
                            </div>
                            <span class="status-pill status-active" id="statusPill"><span class="pulse"></span> Aktif</span>
                        </div>

                        <div class="user-form-foot">
                            <a href="{{ route('admin.users.index') }}" class="user-btn-ghost">Batal</a>
                            <button type="submit" class="user-btn-primary">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Buat Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .user-form-layout{display:grid;gap:1.25rem;max-width:64rem;margin:0 auto}
        @media(min-width:1024px){.user-form-layout{grid-template-columns:280px 1fr;align-items:start}}
        .user-form-side{display:flex;flex-direction:column;gap:1rem;position:sticky;top:5.5rem}
        .user-preview-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.5rem;text-align:center;box-shadow:0 8px 24px rgba(15,23,42,.06);position:relative;overflow:hidden}
        .user-preview-card::before{content:'';position:absolute;top:0;left:0;right:0;height:90px;background:linear-gradient(135deg,#2563eb,#06b6d4,#8b5cf6);opacity:.12}
        .user-preview-ava{width:4.5rem;height:4.5rem;margin:.5rem auto .8rem;border-radius:1.25rem;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;font-weight:800;font-size:1.5rem;display:flex;align-items:center;justify-content:center;position:relative;box-shadow:0 8px 18px rgba(37,99,235,.3);border:3px solid #fff}
        .user-preview-card h4{font-weight:800;color:#0f172a;position:relative}
        .user-preview-card p{font-size:.8rem;color:#64748b;position:relative;word-break:break-all}
        .user-preview-card .role-badge{margin-top:.6rem;position:relative}
        .user-preview-div{height:1px;background:#f1f5f9;margin:1rem 0}
        .user-tips{list-style:none;text-align:left;display:flex;flex-direction:column;gap:.55rem;font-size:.78rem;color:#475569;font-weight:500}
        .user-tips svg{width:.95rem;height:.95rem;color:#22c55e;flex-shrink:0}
        .user-tips li{display:flex;gap:.5rem;align-items:flex-start}
        .user-steps{display:flex;flex-direction:column;gap:.5rem}
        .user-step{display:flex;align-items:center;gap:.7rem;background:#fff;border:1px solid #e2e8f0;border-radius:.9rem;padding:.65rem .9rem;font-size:.82rem;font-weight:700;color:#94a3b8}
        .user-step span{width:1.6rem;height:1.6rem;border-radius:.6rem;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:.78rem}
        .user-step.active{color:#0f172a;border-color:#bfdbfe;background:#eff6ff}
        .user-step.active span{background:#2563eb;color:#fff}
        .user-form-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06);overflow:hidden}
        .user-form{padding:0}
        .user-form-sec{padding:1.4rem 1.5rem;border-bottom:1px solid #f1f5f9}
        .user-form-sec-head{display:flex;gap:.85rem;align-items:center;margin-bottom:1.1rem}
        .user-form-sec-icon{width:2.6rem;height:2.6rem;border-radius:.85rem;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
        .user-form-sec-icon svg{width:1.3rem;height:1.3rem}
        .user-form-sec-icon.blue{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}
        .user-form-sec-icon.violet{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
        .user-form-sec-icon.emerald{background:linear-gradient(135deg,#10b981,#047857)}
        .user-form-sec h3{font-weight:800;color:#0f172a;font-size:.95rem}
        .user-form-sec-head p{font-size:.76rem;color:#64748b}
        .input-icon-wrap{position:relative}
        .input-icon{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:1.05rem;height:1.05rem;color:#94a3b8;pointer-events:none}
        .ui-input.has-icon{padding-left:2.6rem!important;background:#fbfdff!important}
        .ui-input.has-icon:focus{background:#fff!important}
        .ui-input.has-eye{padding-right:2.6rem!important}
        .eye-btn{position:absolute;right:.5rem;top:50%;transform:translateY(-50%);width:1.9rem;height:1.9rem;border-radius:.6rem;display:flex;align-items:center;justify-content:center;color:#94a3b8;border:0;background:transparent;cursor:pointer;font-size:1rem}
        .eye-btn:hover{background:#f1f5f9;color:#0f172a}
        .role-cards{display:grid;gap:.7rem}
        @media(min-width:640px){.role-cards{grid-template-columns:repeat(3,1fr)}}
        .role-card{display:flex;flex-direction:column;align-items:flex-start;gap:.6rem;padding:1rem;border:1.5px solid #e2e8f0;border-radius:1rem;cursor:pointer;transition:.16s;background:#fbfdff;position:relative}
        .role-card:hover{border-color:#93c5fd;background:#fff;transform:translateY(-1px)}
        .role-card.selected{border-color:#2563eb;background:#eff6ff;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
        .role-card strong{display:block;font-size:.85rem;color:#0f172a}
        .role-card small{font-size:.72rem;color:#64748b}
        .role-card-icon{width:2.4rem;height:2.4rem;border-radius:.8rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
        .role-card-icon.gray{background:#f1f5f9}.role-card-icon.blue{background:#dbeafe}.role-card-icon.purple{background:#ede9fe}
        .role-check{position:absolute;top:.6rem;right:.6rem;width:1.4rem;height:1.4rem;border-radius:9999px;background:#2563eb;color:#fff;font-size:.75rem;display:none;align-items:center;justify-content:center;font-weight:800}
        .role-card.selected .role-check{display:flex}
        .pw-meter{height:6px;background:#f1f5f9;border-radius:9999px;margin-top:.6rem;overflow:hidden}
        .pw-meter i{display:block;height:100%;width:0;border-radius:9999px;background:#cbd5e1;transition:width .25s,background-color .25s}
        .status-row{display:flex;align-items:center;gap:.9rem;padding:1.2rem 1.5rem;background:#fbfdff}
        .status-row strong{font-size:.88rem;color:#0f172a}
        .status-row p{font-size:.76rem;color:#64748b}
        .status-row .status-pill{margin-left:auto}
        .switch{position:relative;width:3rem;height:1.65rem;flex-shrink:0}
        .switch input{opacity:0;width:0;height:0}
        .slider{position:absolute;inset:0;background:#cbd5e1;border-radius:9999px;transition:.2s;cursor:pointer}
        .slider::before{content:'';position:absolute;width:1.25rem;height:1.25rem;left:.2rem;top:.2rem;background:#fff;border-radius:9999px;transition:.2s;box-shadow:0 2px 6px rgba(0,0,0,.2)}
        .switch input:checked + .slider{background:linear-gradient(135deg,#22c55e,#16a34a)}
        .switch input:checked + .slider::before{transform:translateX(1.35rem)}
        .status-pill{display:inline-flex;align-items:center;gap:.45rem;font-size:.76rem;font-weight:700;padding:.32rem .75rem;border-radius:9999px;border:1px solid}
        .status-active{background:#dcfce7;border-color:#86efac;color:#166534}
        .status-inactive{background:#f1f5f9;border-color:#e2e8f0;color:#64748b}
        .pulse{width:.5rem;height:.5rem;border-radius:9999px;background:#22c55e;position:relative}
        .pulse::after{content:'';position:absolute;inset:-4px;border-radius:9999px;border:2px solid #22c55e;opacity:.4;animation:ping 1.6s infinite}
        @keyframes ping{75%,100%{transform:scale(1.6);opacity:0}}
        .dot-static{width:.5rem;height:.5rem;border-radius:9999px;background:#94a3b8}
        .user-form-foot{display:flex;justify-content:space-between;align-items:center;gap:.8rem;padding:1.2rem 1.5rem;background:#fff}
        .user-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:700;font-size:.88rem;padding:.75rem 1.4rem;border-radius:.85rem;box-shadow:0 4px 14px rgba(37,99,235,.28);border:0;cursor:pointer;transition:.18s}
        .user-btn-primary svg{width:1rem;height:1rem}
        .user-btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(37,99,235,.34)}
        .user-btn-ghost{display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;font-size:.88rem;padding:.75rem 1.2rem;border-radius:.85rem;text-decoration:none;transition:.18s}
        .user-btn-ghost:hover{background:#f8fafc;border-color:#cbd5e1}
        .role-badge{display:inline-flex;align-items:center;gap:.35rem;font-size:.76rem;font-weight:700;padding:.32rem .7rem;border-radius:9999px;border:1px solid}
        .role-umum{background:#f1f5f9;border-color:#e2e8f0;color:#475569}
        @media(max-width:1023px){.user-form-side{position:static}.user-steps{flex-direction:row}.user-step{flex:1}}
    </style>
    @endpush

    @push('scripts')
    <script>
    (function(){
        const nameI = document.getElementById('name');
        const emailI = document.getElementById('email');
        const pName = document.getElementById('previewName');
        const pEmail = document.getElementById('previewEmail');
        const pAva = document.getElementById('previewAva');
        const pRole = document.getElementById('previewRole');
        function initials(n){ if(!n||!n.trim()) return '?'; return n.trim().split(/\s+/).map(w=>w[0]).slice(0,2).join('').toUpperCase(); }
        function sync(){ pName.textContent = nameI.value || 'Nama Pengguna'; pEmail.textContent = emailI.value || 'email@contoh.com'; pAva.textContent = initials(nameI.value); }
        nameI?.addEventListener('input', sync); emailI?.addEventListener('input', sync); sync();
        document.querySelectorAll('.role-card input').forEach(r=>{
            r.addEventListener('change', ()=>{
                document.querySelectorAll('.role-card').forEach(c=>c.classList.remove('selected'));
                r.closest('.role-card').classList.add('selected');
                const map={umum:'👤 Umum',company:'🏢 Perusahaan',admin:'🛡️ Admin'};
                pRole.textContent = map[r.value]||r.value;
            });
        });
        document.querySelectorAll('.eye-btn').forEach(b=>b.addEventListener('click',()=>{
            const inp=document.getElementById(b.dataset.toggle); if(!inp) return;
            inp.type = inp.type==='password' ? 'text' : 'password';
            const hidden = inp.type==='password';
            b.querySelector('.eye-open')?.classList.toggle('hidden', !hidden);
            b.querySelector('.eye-shut')?.classList.toggle('hidden', hidden);
            b.setAttribute('title', hidden ? 'Tampilkan' : 'Sembunyikan');
        }));
        const pw=document.getElementById('password'), bar=document.getElementById('pwBar'), hint=document.getElementById('pwHint');
        const conf=document.getElementById('password_confirmation'), mHint=document.getElementById('matchHint');
        pw?.addEventListener('input',()=>{
            const v=pw.value, ok=v.length>=8;
            const sc=!v?0:(ok?100:Math.min(Math.round(v.length/8*60),60));
            bar.style.width=sc+'%';
            bar.style.background=!v?'#cbd5e1':(ok?'#16a34a':'#dc2626');
            hint.textContent=!v?'Gunakan minimal 8 karakter.':(ok?'Bagus — sandi memenuhi syarat.':'Kurang — minimal 8 karakter ('+v.length+'/8).');
            hint.style.color=!v?'':(ok?'#16a34a':'#dc2626');
            checkMatch();
        });
        function checkMatch(){ if(!conf.value) return; const ok = pw.value===conf.value; mHint.textContent = ok ? '✅ Sandi cocok!' : '❌ Sandi belum sama.'; mHint.style.color = ok ? '#16a34a' : '#dc2626'; }
        conf?.addEventListener('input', checkMatch);
        const sw=document.getElementById('is_active'), pill=document.getElementById('statusPill');
        sw?.addEventListener('change',()=>{
            if(sw.checked){ pill.className='status-pill status-active'; pill.innerHTML='<span class="pulse"></span> Aktif'; }
            else{ pill.className='status-pill status-inactive'; pill.innerHTML='<span class="dot-static"></span> Nonaktif'; }
        });
    })();
    </script>
    @endpush
</x-app-layout>
