@php
    $company = $job->company;
    $companyName = $company?->name ?? $job->company_name ?? 'Perusahaan';
    $flyerUrl = url()->current();
    $shareText = implode("\n", array_filter([
        "LOWONGAN KERJA",
        "",
        "Posisi: {$job->title}",
        "Perusahaan: {$companyName}",
        "Lokasi: {$job->locationLabel()}",
        "Tipe kerja: " . (\App\Support\Label::jobType($job->job_type)),
        $job->deadline ? "Batas daftar: {$job->deadline->format('d M Y')}" : null,
        "",
        "Keterangan:",
        $job->description,
        "",
        "Kualifikasi:",
        $job->qualifications,
        "",
        "Lihat detail dan daftar:",
        $flyerUrl,
    ], static fn ($line) => $line !== null));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{ $job->title }} - {{ $companyName }}">
    <meta property="og:description" content="Lowongan {{ $job->title }} di {{ $companyName }}">
    <meta property="og:url" content="{{ $flyerUrl }}">
    <title>Flyer {{ $job->title }} - {{ $companyName }}</title>
    <style>
        :root { font-family: Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #e8eef8; color: #172554; }
        .actions { position: fixed; top: 18px; right: 18px; display: flex; gap: 8px; z-index: 2; }
        .actions a, .actions button { border: 0; border-radius: 999px; padding: 10px 15px; background: #fff; color: #172554; font-weight: 700; text-decoration: none; cursor: pointer; box-shadow: 0 4px 14px #17255422; }
        .actions .wa { background: #16a34a; color: #fff; }
        .flyer { width: min(100%, 620px); overflow: hidden; border-radius: 24px; background: #fff; box-shadow: 0 20px 60px #17255433; }
        .head { padding: 38px; color: #fff; background: linear-gradient(135deg, #123b82, #0e7490); }
        .brand { margin-bottom: 30px; font-size: 15px; font-weight: 700; }
        h1 { margin: 0 0 12px; font-size: clamp(28px, 6vw, 46px); line-height: 1.08; }
        .company { margin: 0; font-size: 18px; opacity: .9; }
        .body { padding: 28px 38px 34px; }
        .facts { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px; }
        .fact { padding: 14px; border-radius: 14px; background: #eff6ff; }
        .fact small { display: block; margin-bottom: 5px; color: #64748b; font-size: 11px; text-transform: uppercase; }
        .fact strong { font-size: 15px; }
        h2 { margin: 22px 0 8px; font-size: 17px; }
        .description { white-space: pre-line; line-height: 1.55; color: #475569; }
        .apply { display: block; margin-top: 28px; padding: 15px; border-radius: 12px; background: #f97316; color: #fff; text-align: center; font-weight: 700; text-decoration: none; }
        .hint { margin: 18px 0 0; color: #64748b; font-size: 12px; text-align: center; }
        @media print { body { padding: 0; background: #fff; } .actions, .hint { display: none; } .flyer { width: 100%; box-shadow: none; } }
        @media (max-width: 520px) { body { padding: 12px; } .head, .body { padding-left: 24px; padding-right: 24px; } .actions { top: 8px; right: 8px; } .actions a, .actions button { padding: 8px 11px; font-size: 12px; } }
    </style>
</head>
<body>
    <div class="actions">
        <button class="wa" type="button" onclick="shareTextToWhatsapp()">Bagikan Teks ke WhatsApp</button>
        @if($job->flyer_path)
            <a href="{{ route('jobs.flyer.download', $job) }}">Download Flyer</a>
        @endif
        <button type="button" onclick="window.print()">Cetak / Simpan</button>
    </div>
    <main class="flyer">
        @if($job->flyer_path)
            <img src="{{ route('jobs.flyer.image', $job) }}" alt="Flyer {{ $job->title }}" style="display:block;width:100%;height:auto;">
            <div class="body">
                <a class="apply" href="{{ route('jobs.show', $job) }}">Lihat detail &amp; Lamar Sekarang</a>
            </div>
        @else
        <header class="head">
            <div class="brand">KESEMPATAN KERJA</div>
            <h1>{{ $job->title }}</h1>
            <p class="company">{{ $companyName }}</p>
        </header>
        <section class="body">
            <div class="facts">
                <div class="fact"><small>Lokasi</small><strong>{{ $job->locationLabel() }}</strong></div>
                <div class="fact"><small>Tipe kerja</small><strong>{{ \App\Support\Label::jobType($job->job_type) }}</strong></div>
                @if($job->education)<div class="fact"><small>Pendidikan</small><strong>{{ $job->education }}</strong></div>@endif
                @if($job->deadline)<div class="fact"><small>Batas daftar</small><strong>{{ $job->deadline->format('d M Y') }}</strong></div>@endif
            </div>
            @if($job->description)<h2>Tentang posisi</h2><div class="description">{{ $job->description }}</div>@endif
            @if($job->qualifications)<h2>Kualifikasi</h2><div class="description">{{ $job->qualifications }}</div>@endif
            <a class="apply" href="{{ route('jobs.show', $job) }}">Lihat detail &amp; Lamar Sekarang</a>
            <p class="hint">Bagikan link halaman ini ke WhatsApp agar informasi lowongan mudah diteruskan.</p>
        </section>
        @endif
    </main>
</body>
<script>
    function shareTextToWhatsapp() {
        const whatsappUrl = 'https://wa.me/?text=' + encodeURIComponent(@json($shareText));
        window.open(whatsappUrl, '_blank', 'noopener');
    }
</script>
</html>
