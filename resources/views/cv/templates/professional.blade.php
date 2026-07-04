<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #1f2937;
            background: #fff;
        }
        .layout {
            display: flex;
            min-height: 100%;
        }
        /* Sidebar kiri */
        .sidebar {
            width: 200px;
            background: #1e3a5f;
            color: #fff;
            padding: 24px 16px;
            flex-shrink: 0;
        }
        .sidebar .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
            display: block;
            margin: 0 auto 12px;
        }
        .sidebar .avatar-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28pt;
            font-weight: bold;
            margin: 0 auto 12px;
            color: #fff;
        }
        .sidebar .s-name {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }
        .sidebar .s-role {
            font-size: 8.5pt;
            text-align: center;
            color: rgba(255,255,255,0.7);
            margin-bottom: 16px;
        }
        .sidebar .s-section-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.5);
            margin-top: 16px;
            margin-bottom: 6px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 3px;
        }
        .sidebar .s-item {
            font-size: 9pt;
            color: rgba(255,255,255,0.85);
            margin-bottom: 4px;
            word-break: break-word;
        }
        .sidebar .skill-bar-wrap {
            margin-bottom: 6px;
        }
        .sidebar .skill-name {
            font-size: 9pt;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2px;
        }
        .sidebar .skill-bar {
            height: 5px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
            overflow: hidden;
        }
        .sidebar .skill-fill {
            height: 100%;
            background: #60a5fa;
            border-radius: 3px;
        }

        /* Main content kanan */
        .main {
            flex: 1;
            padding: 24px 24px;
        }
        .main-header {
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .main-name {
            font-size: 20pt;
            font-weight: bold;
            color: #1e3a5f;
        }
        .main-role {
            font-size: 11pt;
            color: #6b7280;
            margin-top: 2px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .section-body {
            font-size: 10pt;
            color: #374151;
            line-height: 1.6;
        }
        .cert-item {
            margin-bottom: 8px;
            padding-left: 12px;
            border-left: 3px solid #60a5fa;
        }
        .cert-title { font-weight: bold; }
        .cert-meta { font-size: 9pt; color: #6b7280; }
    </style>
</head>
<body>
<div class="layout">

    {{-- Sidebar Kiri --}}
    <div class="sidebar">
        {{-- Foto / Initial --}}
        @if($include_photo && $user->avatar)
            <img src="{{ public_path('storage/' . $user->avatar) }}" alt="Foto" class="avatar">
        @else
            <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
        @endif

        <div class="s-name">{{ $user->name }}</div>
        @if($user->student && $user->student->major)
        <div class="s-role">{{ $user->student->major }}</div>
        @endif

        {{-- Kontak --}}
        <div class="s-section-title">Kontak</div>
        <div class="s-item">{{ $user->email }}</div>
        @if($user->phone)<div class="s-item">{{ $user->phone }}</div>@endif
        @if($user->student && $user->student->address)
        <div class="s-item">{{ $user->student->address }}</div>
        @endif

        {{-- Pendidikan --}}
        <div class="s-section-title">Pendidikan</div>
        <div class="s-item">SMK MUTU Karawang</div>
        @if($user->student && $user->student->major)
        <div class="s-item">{{ $user->student->major }}</div>
        @endif
        @if($user->student && $user->student->graduation_year)
        <div class="s-item">Lulus {{ $user->student->graduation_year }}</div>
        @endif

        {{-- Keahlian dengan bar --}}
        @if($include_skills && $user->skills->isNotEmpty())
        <div class="s-section-title">Keahlian</div>
        @foreach($user->skills as $skill)
        <div class="skill-bar-wrap">
            <div class="skill-name">{{ $skill->name }}</div>
            <div class="skill-bar">
                @php $pct = ($skill->pivot->proficiency ?? 3) * 20; @endphp
                <div class="skill-fill" style="width:{{ $pct }}%;"></div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- Main Content Kanan --}}
    <div class="main">
        <div class="main-header">
            <div class="main-name">{{ $user->name }}</div>
            @if($user->student && $user->student->major)
            <div class="main-role">{{ $user->student->major }}
                @if($user->student->graduation_year) · Lulusan {{ $user->student->graduation_year }} @endif
            </div>
            @endif
        </div>

        {{-- Ringkasan --}}
        <div class="section-title">Tentang Saya</div>
        <div class="section-body">{{ $user->bio ?? 'Ringkasan profil belum diisi. Tambahkan 2-3 kalimat tentang minat kerja, kekuatan utama, dan target karir agar sisi kanan CV ini tidak terasa kosong.' }}</div>

        {{-- Pengalaman --}}
        @if($user->student && $user->student->experience)
        <div class="section-title">Pengalaman</div>
        <div class="section-body">{!! nl2br(e($user->student->experience)) !!}</div>
        @endif

        {{-- Sertifikat --}}
        <div class="section-title">Sertifikat</div>
        @if($include_certificates && $user->certificates->isNotEmpty())
            @foreach($user->certificates as $c)
            <div class="cert-item">
                <div class="cert-title">{{ $c->title ?? $c->name ?? '-' }}</div>
                <div class="cert-meta">
                    @if(isset($c->issuer) && $c->issuer) {{ $c->issuer }} @endif
            @if($custom_experience || ($user->student && $user->student->experience))
                </div>
            <div class="section-body">{!! nl2br(e($custom_experience ?: $user->student->experience)) !!}</div>
            @endforeach
        @else
            <div class="section-title">Pencapaian</div>
            <div class="section-body">{{ $custom_achievement ?: 'Tambahkan satu pencapaian utama agar CV terlihat lebih meyakinkan.' }}</div>
            <div class="cert-item">
                <div class="cert-title">Belum ada sertifikat ditampilkan</div>
                <div class="cert-meta">Tambahkan sertifikat di profil untuk memperkuat CV.</div>
            </div>
        @endif
    </div>

</div>
</body>
</html>
