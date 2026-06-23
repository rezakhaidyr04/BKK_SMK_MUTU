<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV - {{ $user->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .header { display:flex; align-items:center; gap:16px; }
        .name { font-size:20px; font-weight:700; }
        .muted { color:#6B7280; font-size:12px; }
        .section { margin-top:12px; }
        .section-title { font-weight:700; font-size:13px; margin-bottom:6px; }
        .skill { display:inline-block; background:#E5E7EB; padding:4px 8px; border-radius:6px; margin:2px; font-size:12px; }
    </style>
</head>
<body>
    <div class="header">
        @if($include_photo && $user->avatar)
            <img src="{{ public_path('storage/' . $user->avatar) }}" alt="avatar" width="80" height="80" style="object-fit:cover;border-radius:8px;">
        @endif
        <div>
            <div class="name">{{ $user->name }}</div>
            <div class="muted">{{ $user->email }} · {{ $user->phone ?? '-' }}</div>
            <div class="muted">{{ $user->student->major ?? '' }} · {{ $user->student->graduation_year ?? '' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Ringkasan</div>
        <div class="muted">{{ $user->bio ?? 'Tidak ada ringkasan.' }}</div>
    </div>

    @if($include_skills && $user->skills->isNotEmpty())
    <div class="section">
        <div class="section-title">Keahlian</div>
        <div>
            @foreach($user->skills as $skill)
                <span class="skill">{{ $skill->name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    @if($user->student && $user->student->experience)
    <div class="section">
        <div class="section-title">Pengalaman</div>
        <div class="muted">{!! nl2br(e($user->student->experience)) !!}</div>
    </div>
    @endif

    @if($include_certificates && $user->certificates->isNotEmpty())
    <div class="section">
        <div class="section-title">Sertifikat</div>
        <ul>
            @foreach($user->certificates as $c)
                <li>{{ $c->name }} — {{ $c->issued_by ?? '' }} ({{ $c->year ?? '' }})</li>
            @endforeach
        </ul>
    </div>
    @endif

</body>
</html>