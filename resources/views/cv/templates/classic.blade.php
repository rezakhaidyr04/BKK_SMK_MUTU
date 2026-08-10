<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #0f172a;
            line-height: 1.5;
            padding: 32px 40px;
            background: #fff;
        }
        .name { font-size: 20px; font-weight: bold; text-align: center; margin-bottom: 6px; }
        .contact { text-align: center; font-size: 10px; margin-bottom: 12px; color: #475569; }
        hr { border: none; border-top: 2px solid #000; margin: 8px 0 14px; }

        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 1px; margin-top: 14px; margin-bottom: 6px; color: #163b66;
        }
        .section-body { font-size: 10.5px; margin-top: 4px; }

        /* Keahlian: dompdf tidak dukung CSS columns, jadi pakai table 2 kolom manual */
        .skill-table { width: 100%; border-collapse: collapse; }
        .skill-table td { vertical-align: top; padding: 1px 8px 1px 0; font-size: 10.5px; }

        .cert-item { margin-bottom: 4px; }
        .cert-title { font-weight: bold; }
        .cert-meta { font-size: 10px; color: #444; }

        .cv-note { color: #64748b; }
        .cv-photo-wrap { text-align: center; margin-top: 12px; }
        .cv-avatar-photo { border-radius: 4px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="name">{{ strtoupper($user->name) }}</div>
    <div class="contact">
        {{ $user->email }}
        @if($user->phone) &middot; {{ $user->phone }} @endif
        @if($user->student && $user->student->preferred_position) &middot; {{ $user->student->preferred_position }} @endif
        @if($user->student && $user->student->graduation_year) &middot; Lulusan {{ $user->student->graduation_year }} @endif
        @if($user->student && $user->student->linkedin_url) &middot; {{ $user->student->linkedin_url }} @endif
        @if($user->student && $user->student->portfolio_url) &middot; {{ $user->student->portfolio_url }} @endif
    </div>
    <hr>

    {{-- Ringkasan --}}
    <div class="section-title">Ringkasan Profesional</div>
    <div class="section-body">{{ $custom_summary ?: ($user->bio ?? '') }}</div>

    {{-- Keahlian --}}
    @if($include_skills && $user->skills->isNotEmpty())
        <div class="section-title">Keahlian</div>
        <div class="section-body">
            <table class="skill-table">
                @php $chunks = $user->skills->chunk(2); @endphp
                @foreach($chunks as $pair)
                    <tr>
                        @foreach($pair as $skill)
                            <td style="width: 50%;">
                                &bull; {{ $skill->name }}
                                @if($skill->pivot->proficiency)
                                    ({{ ['','Pemula','Dasar','Menengah','Mahir','Ahli'][$skill->pivot->proficiency] ?? '' }})
                                @endif
                            </td>
                        @endforeach
                        @if($pair->count() === 1)
                            <td style="width: 50%;"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endif

    {{-- Pendidikan --}}
    @if($user->student)
        <div class="section-title">Pendidikan</div>
        <div class="section-body">
            <strong>SMK MUTU Cikampek</strong>
            @if($user->student->major) &mdash; {{ $user->student->major }} @endif
            @if($user->student->graduation_year)
                <br><span class="cv-note">Tahun Lulus: {{ $user->student->graduation_year }}</span>
            @endif
            @if($user->student->education_history)
                <br><br>{!! nl2br(e($user->student->education_history)) !!}
            @endif
        </div>
    @endif

    {{-- Pengalaman --}}
    @if($custom_experience || ($user->student && $user->student->experience_organization))
        <div class="section-title">Pengalaman</div>
        <div class="section-body">{!! nl2br(e($custom_experience ?: $user->student->experience_organization)) !!}</div>
    @endif

    {{-- Pencapaian --}}
    @if($custom_achievement)
        <div class="section-title">Pencapaian Utama</div>
        <div class="section-body">{{ $custom_achievement }}</div>
    @endif

    {{-- Target & ATS --}}
    @if(!empty($target_position) || !empty($ats_keywords))
        <div class="section-title">Target &amp; Kata Kunci ATS</div>
        <div class="section-body">
            @if(!empty($target_position))<div><strong>Posisi:</strong> {{ $target_position }}</div>@endif
            @if(!empty($ats_keywords))<div><strong>Keyword:</strong> {{ $ats_keywords }}</div>@endif
        </div>
    @endif

    {{-- Sertifikat --}}
    @if($include_certificates && $user->certificates->isNotEmpty())
        <div class="section-title">Sertifikat</div>
        <div class="section-body">
            @foreach($user->certificates as $c)
                <div class="cert-item">
                    <span class="cert-title">{{ $c->title ?? $c->name ?? '-' }}</span>
                    <span class="cert-meta">
                        @if(isset($c->issuer) && $c->issuer) &mdash; {{ $c->issuer }} @endif
                        @if(isset($c->issue_date) && $c->issue_date) ({{ \Carbon\Carbon::parse($c->issue_date)->format('M Y') }}) @endif
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Foto hanya tampil jika diminta (bawah untuk ATS) --}}
    @if($include_photo && $user->avatar)
        <div class="cv-photo-wrap">
            <img src="{{ public_path('storage/' . $user->avatar) }}" alt="Foto" width="80" height="80" class="cv-avatar-photo">
        </div>
    @endif
</body>
</html>