<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan BKK SMK MUTU</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #0f172a; margin: 32px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .meta { color: #64748b; font-size: 12px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 12px; text-align: left; }
        th { background: #f1f5f9; font-weight: 700; }
        td.value { text-align: right; font-variant-numeric: tabular-nums; }
        .footer { margin-top: 24px; color: #94a3b8; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Laporan &amp; Analitik BKK SMK MUTU</h1>
    <p class="meta">Dicetak pada {{ $generatedAt }}</p>

    <table>
        <thead>
            <tr>
                <th>Metrik</th>
                <th style="text-align:right;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td class="value">{{ number_format($row[1], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="footer">Dokumen ini dihasilkan otomatis oleh sistem BKK SMK MUTU.</p>
</body>
</html>
