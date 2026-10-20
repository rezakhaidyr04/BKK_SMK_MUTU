<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MoU {{ $mouNumber }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.6; color: #1e293b; margin: 40px; }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { font-size: 16px; margin: 0 0 4px; letter-spacing: 1px; text-transform: uppercase; }
        .header h2 { font-size: 13px; margin: 0; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        td, th { border: 1px solid #94a3b8; padding: 7px 9px; vertical-align: top; }
        th { background: #f1f5f9; text-align: left; width: 32%; }
        .para { text-align: justify; margin-bottom: 10px; }
        .sign { width: 100%; margin-top: 28px; }
        .sign td { border: none; text-align: center; }
        .sign .name { font-weight: bold; text-decoration: underline; }
        .note { margin-top: 26px; font-size: 9px; color: #64748b; border-top: 1px dashed #cbd5e1; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Surat Perjanjian Kerja Sama</h1>
        <h2>{{ $schoolName }} - BKK</h2>
    </div>

    <p class="para">Nomor: <strong>{{ $mouNumber }}</strong></p>

    <table>
        <tr><th>Para Pihak Pertama</th><td>{{ $schoolName }} (Bursa Kerja Sekolah)</td></tr>
        <tr><th>Alamat</th><td>Jl. Raya Cikampek KM 3, Cikampek Barat, Kabupaten Karawang, Jawa Barat</td></tr>
        <tr><th>Para Pihak Kedua</th><td>{{ $company->name }}</td></tr>
        <tr><th>Bidang Usaha</th><td>{{ $company->industry }}</td></tr>
        <tr><th>Alamat</th><td>{{ $company->address }}</td></tr>
        <tr><th>NPWP</th><td>{{ $company->tax_number ?: '-' }}</td></tr>
        <tr><th>Nomor Telepon</th><td>{{ $company->phone ?: '-' }}</td></tr>
        <tr><th>Email</th><td>{{ $company->email ?: '-' }}</td></tr>
        <tr><th>Tanggal Penandatanganan</th><td>{{ $signedAt }}</td></tr>
        <tr><th>Masa Berlaku</th><td>s.d. {{ $expiresAt }}</td></tr>
    </table>

    <p class="para">
        Perjanjian Kerja Sama ini dibuat dan ditandatangani pada tanggal {{ $issuedAt }} oleh dan antara
        {{ $schoolName }} (selanjutnya disebut <strong>Pihak Pertama</strong>) dan
        {{ $company->name }} (selanjutnya disebut <strong>Pihak Kedua</strong>).
    </p>

    <p class="para">
        Pihak Pertama menyediakan tenaga lulusan siswa SMK yang telah mengikuti pendidikan kejuruan,
        sedangkan Pihak Kedua menyediakan kesempatan magang, penempatan kerja, serta pelatihan yang
        sesuai dengan kebutuhan perusahaan. Perjanjian ini berlaku sampai dengan tanggal {{ $expiresAt }}
        dan dapat diperpanjang berdasarkan kesepakatan kedua belah pihak.
    </p>

    <p class="para">
        Hal-hal lain yang belum diatur dalam perjanjian ini akan diatur kemudian dalam bentuk addendum
        yang ditandatangani oleh kedua belah pihak. Perjanjian ini dibuat dalam dua rangkap, masing-masing
        menyimpan satu rangkap masing-masing pihak.
    </p>

    <table class="sign">
        <tr>
            <td>PIHAK PERTAMA<br>{{ $schoolName }}</td>
            <td>PIHAK KEDUA<br>{{ $company->name }}</td>
        </tr>
        <tr>
            <td style="padding-top: 46px;">&nbsp;</td>
            <td style="padding-top: 46px;">&nbsp;</td>
        </tr>
        <tr>
            <td class="name">{{ $schoolName }}</td>
            <td class="name">{{ $company->name }}</td>
        </tr>
        <tr>
            <td>Direktur / Kepala BKK</td>
            <td>Direktur / Penanggung Jawab</td>
        </tr>
    </table>

    <div class="note">
        Dokumen dummy untuk keperluan pengembangan dan pengujian sistem Mutu Career Center.
        Bukan dokumen kerja sama yang sah.
    </div>
</body>
</html>
