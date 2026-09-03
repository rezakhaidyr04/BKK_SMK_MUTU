<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; background: #f3f6fa; }

        .sheet { border: 1px solid #dce6f0; background: #ffffff; }

        /* Hero header - tabel 2 kolom: identitas kiri, kontak kanan */
        .hero { background-color: #163f73; color: #fff; padding: 24px 28px 20px; border-bottom: 5px solid #38bdf8; }
        .hero-table { width: 100%; border-collapse: collapse; }
        .hero-table td { vertical-align: top; }
        .avatar { width: 72px; height: 72px; border-radius: 8px; }
        .avatar-placeholder {
            width: 72px; height: 72px; text-align: center; vertical-align: middle;
            background-color: #2c5c94; color: #fff; font-size: 26px; font-weight: bold;
            border-radius: 8px;
        }
        .name { font-size: 22px; font-weight: bold; letter-spacing: .2px; }
        .headline { font-size: 12px; margin-top: 5px; color: #dbe7f5; }
        .hero-contact { text-align: right; font-size: 11px; color: #dbe7f5; line-height: 1.6; }
        .pill {
            display: inline-block; background-color: #2c5c94; color: #fff;
            padding: 4px 10px; border-radius: 10px; font-size: 10px; margin-top: 10px; margin-right: 4px;
        }

        /* Body - tabel 2 kolom untuk layout utama */
        .profile-strip { background-color: #f7fbff; border-bottom: 1px solid #dce8f3; padding: 10px 28px; }
        .profile-strip table { width: 100%; border-collapse: collapse; }
        .profile-strip td { width: 33.33%; color: #52677e; font-size: 9.5px; vertical-align: top; }
        .profile-strip strong { display: block; color: #163f73; font-size: 8px; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 2px; }
        .content { padding: 22px 28px 30px; }
        .body-table { width: 100%; border-collapse: collapse; }
        .col-left { width: 58%; vertical-align: top; padding-right: 20px; }
        .col-right { width: 42%; vertical-align: top; }

        .section { margin-bottom: 18px; }
        .section-title {
            font-weight: bold; font-size: 11px; text-transform: uppercase;
            letter-spacing: 1px; color: #163b66; margin-bottom: 7px;
            border-bottom: 2px solid #dbe7f5; padding-bottom: 4px;
            position: relative;
        }
        .section-title:after { content: ''; display: block; width: 34px; height: 2px; background: #38bdf8; position: absolute; left: 0; bottom: -2px; }
        .section-body { font-size: 10.5px; line-height: 1.6; color: #334155; }
        .muted { color: #94a3b8; }

        .card { background-color: #f7fbff; border: 1px solid #dce8f3; border-left: 3px solid #60a5fa; padding: 11px 13px; margin-bottom: 13px; }

        .skill {
            display: inline-block; background-color: #eff6ff; color: #1d4ed8;
            padding: 4px 9px; border-radius: 10px; font-size: 10px; margin: 0 4px 4px 0;
        }
        .skill-empty { color: #94a3b8; font-size: 10px; }

        .list { margin: 0; padding-left: 16px; }
        .list li { margin-bottom: 5px; }
        .contact-line { margin-bottom: 3px; }
        .education-line { border-left: 2px solid #bfdbfe; padding-left: 9px; margin-bottom: 7px; }
        .education-line:last-child { margin-bottom: 0; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="hero">
            <table class="hero-table">
                <tr>
                    <td style="width: 76px;">
                        @if($include_photo && $user->avatar)
                            <img src="{{ public_path('storage/' . $user->avatar) }}" alt="Foto" class="avatar">
                        @else
                            <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="name">{{ $user->name }}</div>
                        <div class="headline">
                            {{ $custom_headline ?: ($user->preferred_position ?? 'Pencari kerja') }}
                        </div>
                        <div>
                            <span class="pill">ATS Friendly</span>
                            <span class="pill">Ringkas</span>
                        </div>
                    </td>
                    <td style="width: 160px;" class="hero-contact">
                        <div>{{ $user->email }}</div>
                        <div>{{ $user->phone ?? '-' }}</div>
                        @if(!empty($user->linkedin_url))<div>LinkedIn tersedia</div>@endif
                        @if(!empty($user->portfolio_url))<div>Portofolio tersedia</div>@endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="profile-strip">
            <table>
                <tr>
                    <td><strong>Posisi Target</strong>{{ $target_position ?: ($user->preferred_position ?: 'Frontend Developer') }}</td>
                    <td><strong>Domisili</strong>{{ $user->address ?: 'Blanakan, Subang, Jawa Barat' }}</td>
                    <td><strong>Tempat, Tanggal Lahir</strong>{{ $user->birth_place ?: 'Subang' }}, {{ $user->birth_date ? $user->birth_date->format('d M Y') : '15 Des 2004' }}</td>
                </tr>
            </table>
        </div>

        <div class="content">
            <table class="body-table">
                <tr>
                    <td class="col-left">
                        <div class="section">
                            <div class="section-title">Ringkasan Profesional</div>
                            <div class="section-body">
                                {{ $custom_summary ?: ($user->bio ?? 'Ringkasan belum diisi. Tambahkan 2-3 kalimat tentang minat, keahlian utama, dan tujuan karir agar CV tidak terlihat kosong.') }}
                            </div>
                        </div>

                        <div class="section">
                            <div class="section-title">Pengalaman</div>
                            @if($custom_experience)
                                <div class="section-body">{!! nl2br(e($custom_experience)) !!}</div>
                            @elseif(!empty($user->experience_organization))
                                <div class="section-body">{!! nl2br(e($user->experience_organization)) !!}</div>
                            @else
                                <div class="section-body muted">Belum ada pengalaman yang ditambahkan. Isi pengalaman magang, organisasi, atau proyek sekolah agar lebih kuat di mata perekrut.</div>
                            @endif
                        </div>

                        <div class="section">
                            <div class="section-title">Pencapaian Utama</div>
                            <div class="section-body">
                                {{ $custom_achievement ?: 'Tambahkan satu pencapaian yang paling meyakinkan: prestasi, proyek, peran kepemimpinan, atau hasil kerja yang bisa dibuktikan.' }}
                            </div>
                        </div>

                        @if(!empty($target_position) || !empty($ats_keywords))
                            <div class="section">
                                <div class="section-title">Target &amp; Kata Kunci ATS</div>
                                <div class="section-body">
                                    @if(!empty($target_position))<div><strong>Posisi:</strong> {{ $target_position }}</div>@endif
                                    @if(!empty($ats_keywords))<div><strong>Keyword:</strong> {{ $ats_keywords }}</div>@endif
                                </div>
                            </div>
                        @endif

                        <div class="section">
                            <div class="section-title">Sertifikat</div>
                            @if($include_certificates && $user->certificates->isNotEmpty())
                                <ul class="list section-body">
                                    @foreach($user->certificates as $c)
                                        <li>
                                            {{ $c->title ?? $c->name ?? '-' }}
                                            @if($c->issuer ?? false)
                                                &mdash; {{ $c->issuer }}
                                            @endif
                                            @if($c->issue_date ?? false)
                                                ({{ \Carbon\Carbon::parse($c->issue_date)->format('M Y') }})
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="section-body muted">Belum ada sertifikat yang ditampilkan.</div>
                            @endif
                        </div>
                    </td>

                    <td class="col-right">
                        <div class="card">
                            <div class="section-title">Kontak</div>
                            <div class="section-body">
                                <div class="contact-line">{{ $user->email }}</div>
                                <div class="contact-line">{{ $user->phone ?? '-' }}</div>
                                <div>{{ $user->address ?? 'Alamat belum diisi' }}</div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-title">Keahlian</div>
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

                        <div class="card">
                            <div class="section-title">Pendidikan</div>
                            <div class="section-body">
                                @if(!empty($user->education_history))
                                    @foreach(preg_split('/\r\n|\r|\n/', $user->education_history) as $education)
                                        @if(trim($education))
                                            <div class="education-line">{{ trim($education) }}</div>
                                        @endif
                                    @endforeach
                                @else
                                    Riwayat pendidikan belum diisi.
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>