<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #111827; margin: 0; padding: 28px; background: #f8fafc; }
        .sheet { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 18px; overflow: hidden; }
        .hero { background: linear-gradient(135deg, #2563eb, #1d4ed8 55%, #0f172a); color: #fff; padding: 28px; }
        .hero-top { display:flex; align-items:center; justify-content:space-between; gap:16px; }
        .header { display:flex; align-items:center; gap:16px; }
        .avatar { width: 76px; height: 76px; border-radius: 16px; object-fit: cover; border: 3px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.12); }
        .avatar-placeholder { width: 76px; height: 76px; border-radius: 16px; display:flex; align-items:center; justify-content:center; font-size: 28px; font-weight: 700; background: rgba(255,255,255,0.14); border: 3px solid rgba(255,255,255,0.25); }
        .name { font-size: 24px; font-weight: 700; line-height: 1.1; }
        .headline { font-size: 13px; opacity: 0.9; margin-top: 4px; }
        .meta { display:flex; flex-wrap:wrap; gap:8px; margin-top:14px; }
        .pill { display:inline-block; background: rgba(255,255,255,0.12); color:#fff; border: 1px solid rgba(255,255,255,0.18); padding: 6px 10px; border-radius: 999px; font-size: 11px; }
        .content { padding: 24px 28px 28px; }
        .grid { display:grid; grid-template-columns: 1.35fr 0.9fr; gap: 20px; }
        .section { margin-top: 18px; }
        .section-title { font-weight:700; font-size:12px; letter-spacing: .08em; text-transform: uppercase; margin-bottom:8px; color:#1d4ed8; }
        .section-body { font-size: 12px; line-height: 1.7; color:#374151; }
        .card { border:1px solid #e5e7eb; border-radius: 14px; padding: 14px; background:#fff; }
        .muted { color:#6B7280; font-size:12px; }
        .skill { display:inline-block; background:#eff6ff; color:#1d4ed8; border:1px solid #dbeafe; padding:5px 10px; border-radius:999px; margin:3px 4px 0 0; font-size:12px; font-weight:600; }
        .skill-empty { display:inline-block; background:#f3f4f6; color:#6b7280; padding:5px 10px; border-radius:999px; margin-top:4px; font-size:12px; }
        .list { margin: 0; padding-left: 18px; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="hero">
            <div class="hero-top">
                <div class="header">
                    @if($include_photo && $user->avatar)
                        <img src="{{ public_path('storage/' . $user->avatar) }}" alt="avatar" class="avatar">
                    @else
                        <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="name">{{ $user->name }}</div>
                        <div class="headline">
                            {{ $custom_headline ?: ($user->student?->major ?? 'Pencari kerja') }}
                            @if($user->student && $user->student->graduation_year) · Lulusan {{ $user->student->graduation_year }} @endif
                        </div>
                    </div>
                </div>
                <div style="text-align:right; font-size:11px; opacity:.9; line-height:1.6;">
                    <div>{{ $user->email }}</div>
                    <div>{{ $user->phone ?? '-' }}</div>
                </div>
            </div>

            <div class="meta">
                <span class="pill">ATS Friendly</span>
                <span class="pill">Ringkas</span>
                <span class="pill">Siap Kirim</span>
            </div>
        </div>

        <div class="content">
            <div class="grid">
                <div>
                    <div class="section">
                        <div class="section-title">Ringkasan Profesional</div>
                        <div class="section-body">{{ $custom_summary ?: ($user->bio ?? 'Ringkasan belum diisi. Tambahkan 2-3 kalimat tentang minat, keahlian utama, dan tujuan karir agar CV tidak terlihat kosong.') }}</div>
                    </div>

                    <div class="section">
                        <div class="section-title">Pengalaman</div>
                        @if($custom_experience)
                            <div class="section-body">{!! nl2br(e($custom_experience)) !!}</div>
                        @elseif($user->student && $user->student->experience)
                            <div class="section-body">{!! nl2br(e($user->student->experience)) !!}</div>
                        @else
                            <div class="section-body muted">Belum ada pengalaman yang ditambahkan. Isi pengalaman magang, organisasi, atau proyek sekolah agar lebih kuat di mata perekrut.</div>
                        @endif
                    </div>

                    <div class="section">
                        <div class="section-title">Pencapaian Utama</div>
                        <div class="section-body">{{ $custom_achievement ?: 'Tambahkan satu pencapaian yang paling meyakinkan: prestasi, proyek, peran kepemimpinan, atau hasil kerja yang bisa dibuktikan.' }}</div>
                    </div>

                    <div class="section">
                        <div class="section-title">Sertifikat</div>
                        @if($include_certificates && $user->certificates->isNotEmpty())
                            <ul class="list section-body">
                                @foreach($user->certificates as $c)
                                    <li>{{ $c->name }} @if($c->issued_by) - {{ $c->issued_by }} @endif @if($c->year) ({{ $c->year }}) @endif</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="section-body muted">Belum ada sertifikat yang ditampilkan.</div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="card">
                        <div class="section-title" style="margin-top:0;">Kontak</div>
                        <div class="section-body">
                            <div>{{ $user->email }}</div>
                            <div>{{ $user->phone ?? '-' }}</div>
                            <div>{{ $user->student?->address ?? 'Alamat belum diisi' }}</div>
                        </div>
                    </div>

                    <div class="section card">
                        <div class="section-title" style="margin-top:0;">Keahlian</div>
                        <div>
                            @if($include_skills && $user->skills->isNotEmpty())
                                @foreach($user->skills as $skill)
                                    <span class="skill">{{ $skill->name }}</span>
                                @endforeach
                            @else
                                <span class="skill-empty">Tambah keahlian di profil</span>
                            @endif
                        </div>
                    </div>

                    <div class="section card">
                        <div class="section-title" style="margin-top:0;">Pendidikan</div>
                        <div class="section-body">
                            <strong>SMK MUTU Karawang</strong><br>
                            {{ $user->student->major ?? 'Jurusan belum diisi' }}<br>
                            {{ $user->student->graduation_year ? 'Lulus ' . $user->student->graduation_year : 'Tahun lulus belum diisi' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>