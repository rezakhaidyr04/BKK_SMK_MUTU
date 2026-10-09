<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan BKKMu</title>
    <style>
        @page { margin: 90px 36px 70px 36px; }
        * { box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; margin: 0; }
        .accent-top { position: fixed; top: -90px; left: -36px; right: -36px; height: 8px; background: #0a1633; }
        .accent-top span { display: block; height: 3px; background: #2563eb; margin-top: 8px; }
        header { margin-bottom: 16px; }
        .title { font-size: 22px; font-weight: bold; color: #0a1633; margin: 0; }
        .subtitle { font-size: 11px; color: #64748b; margin: 2px 0 0; }
        .meta-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; margin: 14px 0 18px; }
        .meta-box table { width: 100%; border: none; }
        .meta-box td { border: none; padding: 2px 0; font-size: 11px; color: #475569; }
        .meta-box td strong { color: #0a1633; }
        .kpi-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .kpi-table td { width: 25%; background: #0a1633; color: #ffffff; text-align: center; padding: 12px 6px; border: 2px solid #ffffff; border-radius: 8px; }
        .kpi-value { font-size: 20px; font-weight: bold; display: block; }
        .kpi-label { font-size: 10px; color: #cbd5e1; display: block; margin-top: 2px; }
        h2.section { font-size: 13px; color: #0a1633; margin: 22px 0 8px; padding-left: 10px; border-left: 4px solid #2563eb; text-transform: uppercase; letter-spacing: 0.04em; }
        p.desc { font-size: 11px; color: #64748b; margin: 0 0 8px; }
        table.data { width: 100%; border-collapse: collapse; font-size: 11.5px; }
        table.data th { background: #0a1633; color: #ffffff; font-weight: bold; text-align: left; padding: 8px 10px; font-size: 11px; }
        table.data th.num, table.data td.num { text-align: right; }
        table.data td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; color: #1e293b; }
        table.data tr.zebra td { background: #f8fafc; }
        table.data tr.total td { background: #e0e7ff; font-weight: bold; color: #0a1633; border-bottom: none; }
        .bar-track { background: #f1f5f9; border-radius: 4px; height: 8px; width: 140px; }
        .bar-fill { background: #2563eb; border-radius: 4px; height: 8px; }
        .footer { position: fixed; bottom: -50px; left: 0; right: 0; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; font-size: 10px; color: #94a3b8; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; background: #f1f5f9; color: #334155; }
    </style>
</head>
<body>
    <div class="accent-top"><span></span></div>

    <header>
        <table style="width:100%;border:none;">
            <tr>
                <td style="border:none;vertical-align:top;">
                    <p class="title">Laporan &amp; Analitik BKKMu</p>
                    <p class="subtitle">BKK SMK TI Muhammadiyah Cikampek &mdash; Ringkasan performa sistem</p>
                </td>
                <td style="border:none;text-align:right;vertical-align:top;">
                    <p style="font-size:11px;color:#0a1633;font-weight:bold;margin:0;">{{ $generatedAt }}</p>
                    <p style="font-size:10px;color:#64748b;margin:2px 0 0;">Dokumen otomatis sistem</p>
                </td>
            </tr>
        </table>
    </header>

    <div class="meta-box">
        <table>
            <tr>
                <td>Tingkat keberhasilan: <strong>{{ $rate }}%</strong> ({{ number_format($summary['accepted_applications'], 0, ',', '.') }} diterima dari {{ number_format($summary['total_applications'], 0, ',', '.') }} lamaran)</td>
                <td style="text-align:right;">Total lowongan aktif: <strong>{{ number_format($summary['active_jobs'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table class="kpi-table">
        <tr>
            <td><span class="kpi-value">{{ number_format($summary['total_umum'], 0, ',', '.') }}</span><span class="kpi-label">Pencari Kerja</span></td>
            <td><span class="kpi-value">{{ number_format($summary['total_jobs'], 0, ',', '.') }}</span><span class="kpi-label">Total Lowongan</span></td>
            <td><span class="kpi-value">{{ number_format($summary['total_applications'], 0, ',', '.') }}</span><span class="kpi-label">Total Lamaran</span></td>
            <td><span class="kpi-value">{{ $rate }}%</span><span class="kpi-label">Keberhasilan</span></td>
        </tr>
    </table>

    <h2 class="section">1. Ringkasan Metrik</h2>
    <p class="desc">Gambaran umum pengguna, lowongan, dan status lamaran.</p>
    <table class="data">
        <thead>
            <tr><th>Metrik</th><th class="num" style="text-align:right;">Nilai</th></tr>
        </thead>
        <tbody>
            @foreach ($rows as $i => $row)
                <tr class="{{ $i % 2 === 1 ? 'zebra' : '' }}">
                    <td>{{ $row[0] }}</td>
                    <td class="num">{{ number_format($row[1], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section">2. Tren Lamaran &mdash; 6 Bulan Terakhir</h2>
    <p class="desc">Jumlah lamaran masuk per bulan.</p>
    @php $maxCount = max($months->pluck('count')->toArray() ?: [1]); $maxCount = max($maxCount, 1); @endphp
    <table class="data">
        <thead>
            <tr><th>Bulan</th><th class="num" style="text-align:right;">Jumlah</th><th style="width:160px;">Grafik</th></tr>
        </thead>
        <tbody>
            @foreach ($months as $i => $month)
                @php $pct = round(($month['count'] / $maxCount) * 100); @endphp
                <tr class="{{ $i % 2 === 1 ? 'zebra' : '' }}">
                    <td>{{ $month['label'] }}</td>
                    <td class="num">{{ number_format($month['count'], 0, ',', '.') }}</td>
                    <td>
                        <div class="bar-track"><div class="bar-fill" style="width:{{ max($pct, 2) }}%;"></div></div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section">3. Pengguna Terbaru</h2>
    <p class="desc">6 pengguna paling baru terdaftar di sistem.</p>
    <table class="data">
        <thead>
            <tr><th>Nama</th><th>Email</th><th>Role</th><th>Terdaftar</th></tr>
        </thead>
        <tbody>
            @forelse ($recentUsers as $i => $user)
                <tr class="{{ $i % 2 === 1 ? 'zebra' : '' }}">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge">{{ \App\Support\Label::role($user->role) }}</span></td>
                    <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;">Belum ada pengguna.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section">4. Lowongan Terbaru</h2>
    <p class="desc">6 lowongan paling baru beserta jumlah pelamar.</p>
    <table class="data">
        <thead>
            <tr><th>Judul</th><th>Perusahaan</th><th>Status</th><th class="num" style="text-align:right;">Pelamar</th></tr>
        </thead>
        <tbody>
            @forelse ($recentJobs as $i => $job)
                <tr class="{{ $i % 2 === 1 ? 'zebra' : '' }}">
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company_name ?? '-' }}</td>
                    <td><span class="badge">{{ \App\Support\Label::jobStatus($job->status) }}</span></td>
                    <td class="num">{{ number_format($job->applications_count ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#94a3b8;">Belum ada lowongan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2 class="section">5. Tracer Study Alumni</h2>
    <p class="desc">KPI BKK: {{ number_format($tracer['filled'] ?? 0, 0, ',', '.') }} terisi ({{ $tracer['fill_rate'] ?? 0 }}%), keselarasan jurusan {{ $tracer['relevance_rate'] ?? 0 }}%.</p>
    <table class="data">
        <thead>
            <tr><th>Metrik</th><th class="num" style="text-align:right;">Nilai</th></tr>
        </thead>
        <tbody>
            @foreach ($tracerRows as $i => $row)
                <tr class="{{ $i % 2 === 1 ? 'zebra' : '' }}">
                    <td>{{ $row[0] }}</td>
                    <td class="num">{{ $row[1] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>Dokumen ini dihasilkan otomatis oleh sistem BKKMu.</td>
                <td style="text-align:right;">Halaman <span class="pagenum"></span></td>
            </tr>
        </table>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont('Helvetica', 'normal');
            $pdf->page_text(520, 810, 'Hal. {PAGE_NUM} / {PAGE_COUNT}', $font, 9, [148, 163, 184]);
        }
    </script>
</body>
</html>
