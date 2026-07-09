<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.5;
            padding: 32px 40px;
        }
        .name {
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .contact {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 16px;
            color: #333;
        }
        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 14px;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        .section-body {
            font-size: 10.5pt;
            margin-top: 4px;
        }
        .skill-list {
            margin: 0;
            padding-left: 18px;
            columns: 2;
        }
        .skill-list li {
            margin-bottom: 2px;
        }
        .cert-item {
            margin-bottom: 4px;
        }
        .cert-title {
            font-weight: bold;
        }
        .cert-meta {
            font-size: 10pt;
            color: #444;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="name">{{ strtoupper($user->name) }}</div>
    <div class="contact">
        {{ $user->email }}
        @if($user->phone) · {{ $user->phone }} @endif
        @if($user->student && $user->student->major) · {{ $user->student->major }} @endif
        @if($user->student && $user->student->graduation_year) · Lulusan {{ $user->student->graduation_year }} @endif
    </div>
    <hr>

    {{-- Ringkasan --}}
    @if($user->bio)
    <div class="section-title">Ringkasan Profesional</div>
    <div class="section-body">{{ $user->bio }}</div>
    @endif

    {{-- Keahlian --}}
    @if($include_skills && $user->skills->isNotEmpty())
    <div class="section-title">Keahlian</div>
    <div class="section-body">
        <ul class="skill-list">
            @foreach($user->skills as $skill)
                <li>{{ $skill->name }}
                    @if($skill->pivot->proficiency)
                        ({{ ['','Pemula','Dasar','Menengah','Mahir','Ahli'][$skill->pivot->proficiency] ?? '' }})
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Pendidikan --}}
        @if($user->student)
    <div class="section-title">Pendidikan</div>
    <div class="section-body">
        <strong>SMK MUTU Cikampek</strong>
        @if($user->student->major) — {{ $user->student->major }} @endif
        @if($user->student->graduation_year)
            <br><span style="color:#444;">Tahun Lulus: {{ $user->student->graduation_year }}</span>
        @endif
    </div>
    @endif

    {{-- Pengalaman --}}
    @if($user->student && $user->student->experience)
    <div class="section-title">Pengalaman</div>
    <div class="section-body">{!! nl2br(e($user->student->experience)) !!}</div>
    @endif

    {{-- Sertifikat --}}
        @if($custom_summary || $user->bio)
    <div class="section-body">
        <div class="section-body">{{ $custom_summary ?: $user->bio }}</div>
            @foreach($user->certificates as $c)
            <div class="cert-item">
                <span class="cert-title">{{ $c->title ?? $c->name ?? '-' }}</span>
                <span class="cert-meta">
                    @if(isset($c->issuer) && $c->issuer) — {{ $c->issuer }} @endif
                    @if(isset($c->issue_date) && $c->issue_date) ({{ \Carbon\Carbon::parse($c->issue_date)->format('M Y') }}) @endif
                </span>
            </div>
            @endforeach
        @else
            Belum ada sertifikat yang ditampilkan.
        @endif
    </div>

    {{-- Foto hanya tampil jika diminta (bawah untuk ATS) --}}
    @if($include_photo && $user->avatar)
    <div style="text-align:center; margin-top:16px;">
        <img src="{{ public_path('storage/' . $user->avatar) }}" alt="Foto" width="80" height="80" style="object-fit:cover; border-radius:4px;">
        {{-- Pencapaian --}}
        <div class="section-title">Pencapaian Utama</div>
        <div class="section-body">{{ $custom_achievement ?: 'Contoh: menyelesaikan proyek kelas, pengalaman magang, kepanitiaan, atau penghargaan yang relevan.' }}</div>

        {{-- Pengalaman --}}
        @if($custom_experience || ($user->student && $user->student->experience))

        <div class="section-body">{!! nl2br(e($custom_experience ?: $user->student->experience)) !!}</div>
</html>
