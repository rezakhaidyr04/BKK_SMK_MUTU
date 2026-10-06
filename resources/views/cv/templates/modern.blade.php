<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Georgia, "Times New Roman", Times, serif; color: #111111; font-size: 11.5pt; line-height: 1.5; }
        .page { padding: 48px 52px 56px; }

        .header { text-align: center; margin-bottom: 14px; }
        .name { font-size: 20pt; font-weight: bold; text-transform: uppercase; }
        .contact { font-size: 10.5pt; margin-top: 4px; }

        .section { margin-top: 15px; }
        .section-title {
            font-size: 13pt; font-weight: bold; text-transform: uppercase;
            border-bottom: 1.5pt solid #111111; padding-bottom: 2px; margin-bottom: 7px;
        }
        ul.dots { margin: 0 0 0 20px; padding: 0; }
        ul.dots li { margin-bottom: 4px; }
        .muted { color: #555555; }
    </style>
</head>
<body>
    <div class="page">
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        <div class="contact">{{ $user->address ?: 'Indonesia' }} | HP: {{ $user->phone ?: '-' }} | Email: {{ $user->email }}</div>
    </div>

    <div class="section">
        <div class="section-title">Ringkasan</div>
        <div>{!! nl2br(e(str_replace(['\\r\\n', '\\n', '\\r'], "\n", $custom_summary ?: ($user->bio ?? 'Lulusan SMK yang disiplin, siap bekerja, dan cepat mempelajari hal baru.')))) !!}</div>
    </div>

    <div class="section">
        <div class="section-title">Pengalaman</div>
        @if($custom_experience)
            <div>{!! nl2br(e($custom_experience)) !!}</div>
        @elseif(!empty($user->experience_organization))
            <div>{!! nl2br(e(str_replace(['\\r\\n', '\\n', '\\r'], "\n", $user->experience_organization))) !!}</div>
        @else
            <div class="muted">Pengalaman magang, organisasi, atau proyek sekolah.</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Pendidikan</div>
        @if(!empty($user->education_history))
            <div>{!! nl2br(e(str_replace(['\\r\\n', '\\n', '\\r'], "\n", $user->education_history))) !!}</div>
        @else
            <div class="muted">Riwayat pendidikan belum diisi.</div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Kemampuan</div>
        @php
            $extraKeywords = collect(explode(',', $ats_keywords ?? ''))
                ->map(fn($k) => trim($k))->filter()->values();
            $existingSkills = $user->skills->pluck('name')
                ->map(fn($n) => mb_strtolower(trim($n)));
            $extraKeywords = $extraKeywords
                ->reject(fn($k) => $existingSkills->contains(mb_strtolower($k)))->values();
        @endphp
        @if($include_skills && ($user->skills->isNotEmpty() || $extraKeywords->isNotEmpty()))
            <ul class="dots">
                @foreach($user->skills as $skill)
                    <li>{{ $skill->name }}</li>
                @endforeach
                @foreach($extraKeywords as $kw)
                    <li>{{ $kw }}</li>
                @endforeach
            </ul>
        @else
            <div class="muted">Komunikasi, kerja tim, Microsoft Office.</div>
        @endif
    </div>

    @if($include_certificates && $user->certificates->isNotEmpty())
        <div class="section">
            <div class="section-title">Sertifikat</div>
            <ul class="dots">
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
        </div>
    @endif

    @if(!empty($custom_achievement))
        <div class="section">
            <div class="section-title">Pencapaian</div>
            <div>{{ $custom_achievement }}</div>
        </div>
    @endif
    </div>
</body>
</html>