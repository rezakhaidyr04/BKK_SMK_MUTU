<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; background: #fff; }

        .layout-table { width: 100%; border-collapse: collapse; }
        .sidebar { width: 32%; background-color: #0f172a; color: #fff; padding: 26px 20px; vertical-align: top; }
        .main { width: 68%; padding: 26px 34px; vertical-align: top; }

        .avatar { width: 92px; height: 92px; border-radius: 10px; }
        .avatar-placeholder {
            width: 92px; height: 92px; text-align: center; vertical-align: middle;
            background-color: #1e293b; color: #fff; font-size: 32px; font-weight: bold;
            border-radius: 10px;
        }
        .avatar-wrap-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .avatar-wrap-table td { text-align: center; }

        .sidebar-title { font-size: 15px; font-weight: bold; margin-top: 14px; text-align: center; }
        .sidebar-subtitle { font-size: 11px; color: #cbd5e1; margin-top: 4px; text-align: center; }

        .section-title-dark {
            font-size: 11px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 8px; margin-top: 22px; color: #94a3b8;
        }
        .info-item { font-size: 10.5px; margin-bottom: 7px; color: #e2e8f0; }

        .skill-chip {
            display: inline-block; margin: 0 5px 5px 0; padding: 4px 9px;
            color: #0f172a; background-color: #e2e8f0; border-radius: 9px; font-size: 9.5px;
        }

        .main-title { font-size: 24px; font-weight: bold; }
        .main-headline { font-size: 12px; color: #475569; margin-top: 6px; }
        .main-meta { font-size: 10.5px; color: #475569; margin-top: 10px; }

        .section-title { font-size: 11px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin-top: 18px; margin-bottom: 8px; color: #163b66; }
        .section-body { font-size: 10.5px; line-height: 1.6; color: #334155; }
        .card { background-color: #f8fafc; padding: 14px 16px; }

        ul.list { padding-left: 16px; margin-top: 6px; color: #334155; }
        ul.list li { margin-bottom: 6px; }
    </style>
</head>
<body>
    <table class="layout-table">
        <tr>
            <td class="sidebar">
                <table class="avatar-wrap-table">
                    <tr>
                        <td>
                            @if(!empty($include_photo) && $user->avatar)
                                <img src="{{ public_path('storage/' . $user->avatar) }}" alt="Foto" class="avatar">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="sidebar-title">{{ $user->name }}</div>
                <div class="sidebar-subtitle">{{ $user->student?->preferred_position ?: ($user->student?->major ?? 'Pencari kerja') }}</div>

                <div class="section-title-dark">Kontak</div>
                <div class="info-item">{{ $user->email }}</div>
                @if($user->phone)<div class="info-item">{{ $user->phone }}</div>@endif
                @if($user->student && $user->student->address)<div class="info-item">{{ $user->student->address }}</div>@endif
                @if($user->student && $user->student->linkedin_url)<div class="info-item">LinkedIn tersedia</div>@endif
                @if($user->student && $user->student->portfolio_url)<div class="info-item">Portofolio tersedia</div>@endif

                @if(!empty($include_skills) && $user->skills->isNotEmpty())
                    <div class="section-title-dark">Keahlian</div>
                    <div>
                        @foreach($user->skills as $skill)
                            <span class="skill-chip">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif

                @if(!empty($include_certificates) && $user->certificates->isNotEmpty())
                    <div class="section-title-dark">Sertifikat</div>
                    @foreach($user->certificates as $c)
                        <div class="info-item">{{ $c->title ?? $c->name }}</div>
                    @endforeach
                @endif
            </td>

            <td class="main">
                <div class="main-title">{{ $user->name }}</div>
                <div class="main-headline">{{ $custom_headline ?: ($user->student?->preferred_position ?: ($user->student?->major ?? 'Pencari kerja dengan motivasi tinggi')) }}</div>
                <div class="main-meta">
                    @if($user->student && $user->student->graduation_year)Lulus {{ $user->student->graduation_year }}@endif
                    @if($user->student && $user->student->major) &middot; {{ $user->student->major }}@endif
                </div>

                <div class="card" style="margin-top: 18px;">
                    <div class="section-title" style="margin-top: 0;">Ringkasan Profesional</div>
                    <div class="section-body">{{ $custom_summary ?: ($user->bio ?? 'Ringkasan profil belum diisi. Tambahkan 2-3 kalimat tentang minat kerja, kekuatan utama, dan tujuan karir.') }}</div>
                </div>

                <div class="section-title">Pendidikan</div>
                <div class="section-body">
                    <strong>SMK MUTU Cikampek</strong><br>
                    {{ $user->student?->major ?? 'Jurusan belum diisi' }}
                    @if($user->student && $user->student->graduation_year)<br>Lulus {{ $user->student->graduation_year }}@endif
                    @if($user->student && $user->student->education_history)
                        <br><br>{!! nl2br(e($user->student->education_history)) !!}
                    @endif
                </div>

                @if($custom_experience || ($user->student && $user->student->experience_organization))
                    <div class="section-title">Pengalaman &amp; Organisasi</div>
                    <div class="section-body">{!! nl2br(e($custom_experience ?: $user->student->experience_organization)) !!}</div>
                @endif

                @if($custom_achievement)
                    <div class="section-title">Pencapaian Utama</div>
                    <div class="section-body">{{ $custom_achievement }}</div>
                @endif

                @if(!empty($target_position) || !empty($ats_keywords))
                    <div class="section-title">Target &amp; Kata Kunci ATS</div>
                    <div class="section-body">
                        @if(!empty($target_position))<div><strong>Posisi:</strong> {{ $target_position }}</div>@endif
                        @if(!empty($ats_keywords))<div><strong>Keyword:</strong> {{ $ats_keywords }}</div>@endif
                    </div>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>