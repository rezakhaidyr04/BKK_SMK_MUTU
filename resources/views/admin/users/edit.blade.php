<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Ubah Pengguna" subtitle="Perbarui data dan kontrol akses pengguna." eyebrow="Admin › Pengguna">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Pengguna
                </span>
                <span class="page-banner__chip">{{ $user->name }}</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.users.show', $user) }}" variant="white" size="sm">Lihat Detail</x-ui.btn>
                <x-ui.btn href="{{ route('admin.users.index') }}" variant="white" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>

        <div class="page-container page-section">
            @if($errors->any())
            <x-ui.alert type="danger" class="mb-6 max-w-4xl mx-auto">
                <ul class="list-disc pl-4 space-y-1 text-sm">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </x-ui.alert>
            @endif

            <div class="user-form-layout" data-reveal>
                <aside class="user-form-side">
                    <div class="user-preview-card">
                        @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="user-preview-img">
                        @else
                        <div class="user-preview-ava" id="previewAva">{{ strtoupper(collect(explode(' ', trim($user->name)))->map(fn($w)=>mb_substr($w,0,1))->take(2)->join('')) }}</div>
                        @endif
                        <h4 id="previewName">{{ $user->name }}</h4>
                        <p id="previewEmail">{{ $user->email }}</p>
                        <div class="preview-badges">
                            <span class="role-badge {{ $user->role==='admin' ? 'role-admin' : ($user->role==='company' ? 'role-company' : 'role-umum') }}" id="previewRole">{{ ucfirst($user->role) }}</span>
                            <span class="status-pill {{ $user->is_active ? 'status-active' : 'status-inactive' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <div class="user-preview-div"></div>
                        <dl class="user-meta-list">
                            <div><dt>Terdaftar</dt><dd>{{ $user->created_at->format('d M Y') }}</dd></div>
                            <div><dt>ID</dt><dd>#{{ $user->id }}</dd></div>
                            <div><dt>Verifikasi</dt><dd>{{ $user->email_verified_at ? 'Sudah' : 'Belum' }}</dd></div>
                        </dl>
                        @if($user->id === auth()->id())
                        <div class="self-warning">⚠️ Ini akun Anda sendiri — role tidak bisa diubah sembarangan.</div>
                        @endif
                    </div>
                </aside>

                <div class="user-form-card">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="user-form">
                        @csrf
                        @method('PUT')

                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div><h3>Informasi Akun</h3><p>Nama & email login</p></div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="ui-label" for="name">Nama <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="ui-input has-icon">
                                    </div>
                                    @error('name')<p class="ui-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="ui-label" for="email">Email <span class="text-red-500">*</span></label>
                                    <div class="input-icon-wrap">
                                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="ui-input has-icon">
                                    </div>
                                    @error('email')<p class="ui-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon violet">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.6 2A9 9 0 1112 3a9 9 0 015.6 17z"/></svg>
                                </div>
                                <div><h3>Peran <span class="text-red-500">*</span></h3><p>Hak akses pengguna</p></div>
                            </div>
                            @php $curRole = old('role', $user->role); $isSelf = $user->id === auth()->id(); @endphp
                            <div class="role-cards">
                                <label class="role-card {{ $curRole==='umum' ? 'selected' : '' }} {{ $isSelf ? 'disabled' : '' }}">
                                    <input type="radio" name="role" value="umum" {{ $curRole==='umum' ? 'checked' : '' }} class="sr-only" {{ $isSelf ? 'disabled' : '' }}>
                                    <div class="role-card-icon gray">👤</div>
                                    <div><strong>Pengguna Umum</strong><small>Pencari kerja / alumni</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                                <label class="role-card {{ $curRole==='company' ? 'selected' : '' }} {{ $isSelf ? 'disabled' : '' }}">
                                    <input type="radio" name="role" value="company" {{ $curRole==='company' ? 'checked' : '' }} class="sr-only" {{ $isSelf ? 'disabled' : '' }}>
                                    <div class="role-card-icon blue">🏢</div>
                                    <div><strong>Perusahaan</strong><small>Posting lowongan</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                                <label class="role-card {{ $curRole==='admin' ? 'selected' : '' }} {{ $isSelf ? 'disabled' : '' }}">
                                    <input type="radio" name="role" value="admin" {{ $curRole==='admin' ? 'checked' : '' }} class="sr-only" {{ $isSelf ? 'disabled' : '' }}>
                                    <div class="role-card-icon purple">🛡️</div>
                                    <div><strong>Admin</strong><small>Akses penuh</small></div>
                                    <div class="role-check">✓</div>
                                </label>
                            </div>
                            @if($isSelf)<input type="hidden" name="role" value="{{ $user->role }}">@endif
                            @error('role')<p class="ui-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="user-form-sec">
                            <div class="user-form-sec-head">
                                <div class="user-form-sec-icon emerald">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <div><h3>Keamanan</h3><p>Kosongkan jika tidak ingin mengganti password</p></div>
                            </div>
                            <div>
                                <label class="ui-label" for="password">Password Baru</label>
                                <div class="input-icon-wrap">
                                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak diganti" class="ui-input has-icon has-eye" minlength="8">
                                    <button type="button" class="eye-btn" data-toggle="password">👁</button>
                                </div>
                                @error('password')<p class="ui-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="status-row">
                            <label class="switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            <div><strong>Status akun</strong><p>{{ $user->is_active ? 'Akun sedang aktif' : 'Akun sedang nonaktif' }}</p></div>
                            <span class="status-pill {{ old('is_active', $user->is_active) ? 'status-active' : 'status-inactive' }}" id="statusPill">
                                {{ old('is_active', $user->is_active) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <div class="user-form-foot">
                            <a href="{{ route('admin.users.index') }}" class="user-btn-ghost">Batal</a>
                            <button type="submit" class="user-btn-primary">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan
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
        .user-form-side{position:sticky;top:5.5rem}
        .user-preview-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;padding:1.5rem;text-align:center;box-shadow:0 8px 24px rgba(15,23,42,.06);position:relative;overflow:hidden}
        .user-preview-card::before{content:'';position:absolute;top:0;left:0;right:0;height:90px;background:linear-gradient(135deg,#2563eb,#06b6d4,#8b5cf6);opacity:.12}
        .user-preview-ava{width:4.5rem;height:4.5rem;margin:.5rem auto .8rem;border-radius:1.25rem;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;font-weight:800;font-size:1.4rem;display:flex;align-items:center;justify-content:center;position:relative;border:3px solid #fff;box-shadow:0 8px 18px rgba(37,99,235,.3)}
        .user-preview-img{width:4.5rem;height:4.5rem;margin:.5rem auto .8rem;border-radius:1.25rem;object-fit:cover;position:relative;border:3px solid #fff;box-shadow:0 8px 18px rgba(15,23,42,.15)}
        .user-preview-card h4{font-weight:800;color:#0f172a;position:relative}
        .user-preview-card p{font-size:.8rem;color:#64748b;position:relative;word-break:break-all}
        .preview-badges{display:flex;gap:.4rem;justify-content:center;margin-top:.6rem;position:relative;flex-wrap:wrap}
        .user-preview-div{height:1px;background:#f1f5f9;margin:1rem 0}
        .user-meta-list{display:flex;flex-direction:column;gap:.5rem;text-align:left}
        .user-meta-list div{display:flex;justify-content:space-between;font-size:.78rem}
        .user-meta-list dt{color:#64748b;font-weight:600}
        .user-meta-list dd{color:#0f172a;font-weight:700}
        .self-warning{margin-top:1rem;background:#fef3c7;border:1px solid #fde68a;color:#92400e;font-size:.75rem;font-weight:600;border-radius:.8rem;padding:.6rem .8rem}
        .user-form-card{background:#fff;border:1px solid #e2e8f0;border-radius:1.25rem;box-shadow:0 8px 24px rgba(15,23,42,.06);overflow:hidden}
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
        .eye-btn{position:absolute;right:.5rem;top:50%;transform:translateY(-50%);width:1.9rem;height:1.9rem;border-radius:.6rem;display:flex;align-items:center;justify-content:center;color:#94a3b8;border:0;background:transparent;cursor:pointer}
        .eye-btn:hover{background:#f1f5f9}
        .role-cards{display:grid;gap:.7rem}
        @media(min-width:640px){.role-cards{grid-template-columns:repeat(3,1fr)}}
        .role-card{display:flex;flex-direction:column;gap:.6rem;padding:1rem;border:1.5px solid #e2e8f0;border-radius:1rem;cursor:pointer;transition:.16s;background:#fbfdff;position:relative}
        .role-card:hover:not(.disabled){border-color:#93c5fd;transform:translateY(-1px)}
        .role-card.selected{border-color:#2563eb;background:#eff6ff;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
        .role-card.disabled{opacity:.6;cursor:not-allowed}
        .role-card strong{display:block;font-size:.85rem;color:#0f172a}
        .role-card small{font-size:.72rem;color:#64748b}
        .role-card-icon{width:2.4rem;height:2.4rem;border-radius:.8rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
        .role-card-icon.gray{background:#f1f5f9}.role-card-icon.blue{background:#dbeafe}.role-card-icon.purple{background:#ede9fe}
        .role-check{position:absolute;top:.6rem;right:.6rem;width:1.4rem;height:1.4rem;border-radius:9999px;background:#2563eb;color:#fff;font-size:.75rem;display:none;align-items:center;justify-content:center;font-weight:800}
        .role-card.selected .role-check{display:flex}
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
        .status-pill{display:inline-flex;align-items:center;gap:.4rem;font-size:.76rem;font-weight:700;padding:.32rem .75rem;border-radius:9999px;border:1px solid}
        .status-active{background:#dcfce7;border-color:#86efac;color:#166534}
        .status-inactive{background:#f1f5f9;border-color:#e2e8f0;color:#64748b}
        .role-badge{display:inline-flex;align-items:center;font-size:.76rem;font-weight:700;padding:.32rem .7rem;border-radius:9999px;border:1px solid}
        .role-admin{background:#ede9fe;border-color:#c4b5fd;color:#5b21b6}
        .role-company{background:#dbeafe;border-color:#93c5fd;color:#1d4ed8}
        .role-umum{background:#f1f5f9;border-color:#e2e8f0;color:#475569}
        .user-form-foot{display:flex;justify-content:space-between;padding:1.2rem 1.5rem}
        .user-btn-primary{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:700;font-size:.88rem;padding:.75rem 1.4rem;border-radius:.85rem;box-shadow:0 4px 14px rgba(37,99,235,.28);border:0;cursor:pointer}
        .user-btn-primary svg{width:1rem;height:1rem}
        .user-btn-primary:hover{transform:translateY(-1px)}
        .user-btn-ghost{background:#fff;border:1px solid #e2e8f0;color:#475569;font-weight:600;padding:.75rem 1.2rem;border-radius:.85rem;text-decoration:none}
        .user-btn-ghost:hover{background:#f8fafc}
        @media(max-width:1023px){.user-form-side{position:static}}
    </style>
    @endpush

    @push('scripts')
    <script>
    (function(){
        const nameI=document.getElementById('name'), emailI=document.getElementById('email');
        const pName=document.getElementById('previewName'), pEmail=document.getElementById('previewEmail'), pAva=document.getElementById('previewAva');
        function initials(n){ if(!n||!n.trim()) return '?'; return n.trim().split(/\s+/).map(w=>w[0]).slice(0,2).join('').toUpperCase(); }
        function sync(){ if(pName) pName.textContent=nameI.value||'—'; if(pEmail) pEmail.textContent=emailI.value||'—'; if(pAva) pAva.textContent=initials(nameI.value); }
        nameI?.addEventListener('input',sync); emailI?.addEventListener('input',sync);
        document.querySelectorAll('.role-card input:not(:disabled)').forEach(r=>r.addEventListener('change',()=>{
            document.querySelectorAll('.role-card').forEach(c=>c.classList.remove('selected'));
            r.closest('.role-card').classList.add('selected');
            const pr=document.getElementById('previewRole'); if(pr) pr.textContent=r.value.charAt(0).toUpperCase()+r.value.slice(1);
        }));
        document.querySelectorAll('.eye-btn').forEach(b=>b.addEventListener('click',()=>{
            const i=document.getElementById(b.dataset.toggle); if(!i) return;
            i.type=i.type==='password'?'text':'password'; b.textContent=i.type==='password'?'👁':'🙈';
        }));
        const sw=document.getElementById('is_active'), pill=document.getElementById('statusPill');
        sw?.addEventListener('change',()=>{
            if(sw.checked){ pill.className='status-pill status-active'; pill.textContent='Aktif'; }
            else{ pill.className='status-pill status-inactive'; pill.textContent='Nonaktif'; }
        });
    })();
    </script>
    @endpush
</x-app-layout>
