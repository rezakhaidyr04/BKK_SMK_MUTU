<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; background: #ffffff; }

        .sheet { border: 1px solid #e6eef6; }

        /* Hero header - tabel 2 kolom: identitas kiri, kontak kanan */
        .hero { background-color: #1f4f8a; color: #fff; padding: 22px 26px; }
        .hero-table { width: 100%; border-collapse: collapse; }
        .hero-table td { vertical-align: top; }
        .avatar { width: 72px; height: 72px; border-radius: 8px; }
        .avatar-placeholder {
            width: 72px; height: 72px; text-align: center; vertical-align: middle;
            background-color: #2c5c94; color: #fff; font-size: 26px; font-weight: bold;
            border-radius: 8px;
        }
        .name { font-size: 20px; font-weight: bold; }
        .headline { font-size: 12px; margin-top: 4px; color: #dbe7f5; }
        .hero-contact { text-align: right; font-size: 11px; color: #dbe7f5; line-height: 1.6; }
        .pill {
            display: inline-block; background-color: #2c5c94; color: #fff;
            padding: 4px 10px; border-radius: 10px; font-size: 10px; margin-top: 10px; margin-right: 4px;
        }

        /* Body - tabel 2 kolom untuk layout utama */
        .content { padding: 20px 26px 26px; }
        .body-table { width: 100%; border-collapse: collapse; }
        .col-left { width: 58%; vertical-align: top; padding-right: 20px; }
        .col-right { width: 42%; vertical-align: top; }

        .section { margin-bottom: 14px; }
        .section-title {
            font-weight: bold; font-size: 11px; text-transform: uppercase;
            letter-spacing: 1px; color: #163b66; margin-bottom: 6px;
            border-bottom: 1px solid #dbe7f5; padding-bottom: 3px;
        }
        .section-body { font-size: 10.5px; line-height: 1.6; color: #334155; }
        .muted { color: #94a3b8; }

        .card { background-color: #f8fafc; border: 1px solid #eef6fb; padding: 10px 12px; margin-bottom: 12px; }

        .skill {
            display: inline-block; background-color: #eef2ff; color: #1d4ed8;
            padding: 4px 9px; border-radius: 10px; font-size: 10px; margin: 0 4px 4px 0;
        }
        .skill-empty { color: #94a3b8; font-size: 10px; }

        .list { margin: 0; padding-left: 16px; }
        .list li { margin-bottom: 4px; }
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
                                <div>{{ $user->email }}</div>
                                <div>{{ $user->phone ?? '-' }}</div>
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
                                    {!! nl2br(e($user->education_history)) !!}
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