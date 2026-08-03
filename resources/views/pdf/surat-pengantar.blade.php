<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Kerja</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            margin: 40px;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 24px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 20px;
        }
        .kop-surat p {
            margin: 0;
            font-size: 12px;
        }
        .nomor-surat {
            text-align: left;
            margin-bottom: 20px;
        }
        .isi-surat {
            text-align: justify;
        }
        .tanda-tangan {
            margin-top: 50px;
            float: right;
            text-align: center;
        }
        .tanda-tangan p {
            margin: 0;
        }
        .ttd-space {
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h1>BURSA KERJA KHUSUS (BKK)</h1>
        <h2>SMK MUTU</h2>
        <p>Jl. Contoh Alamat No. 123, Kota, Provinsi</p>
        <p>Telp: (021) 1234567 | Email: bkk@smkmutu.sch.id</p>
    </div>

    <div class="nomor-surat">
        <p>Nomor : BKK-SMK/SP/{{ date('Y/m/d') }}/{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }}<br>
        Hal : <strong>Surat Pengantar Lamaran Pekerjaan</strong></p>
    </div>

    <div class="isi-surat">
        <p>Kepada Yth.,<br>
        <strong>HRD {{ $application->job->company_name ?? 'Perusahaan' }}</strong><br>
        di Tempat</p>

        <p>Dengan hormat,</p>
        <p>Sehubungan dengan adanya lowongan pekerjaan di perusahaan yang Bapak/Ibu pimpin untuk posisi <strong>{{ $application->job->title }}</strong>, maka dengan ini kami dari Bursa Kerja Khusus (BKK) SMK MUTU memberikan pengantar kepada alumni/siswa kami:</p>

        <table style="margin-left: 20px; margin-bottom: 15px;">
            <tr>
                <td width="150">Nama</td>
                <td width="10">:</td>
                <td><strong>{{ $application->user->name }}</strong></td>
            </tr>
            <tr>
                <td>Jurusan / Keahlian</td>
                <td>:</td>
                <td>{{ optional($application->user->student)->major ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $application->user->email }}</td>
            </tr>
        </table>

        <p>Bahwa nama tersebut di atas adalah benar siswa/alumni SMK MUTU yang berkelakuan baik dan bermaksud untuk melamar pekerjaan di perusahaan Bapak/Ibu. Sebagai bahan pertimbangan, bersama ini siswa yang bersangkutan telah melampirkan berkas-berkas lamaran pekerjaan.</p>

        <p>Demikian surat pengantar ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    <div class="tanda-tangan">
        <p>Jakarta, {{ date('d F Y') }}</p>
        <p>Ketua BKK SMK MUTU,</p>
        <div class="ttd-space"></div>
        <p><strong><u>Nama Ketua BKK, M.Pd</u></strong></p>
        <p>NIP. 19800101 200501 1 001</p>
    </div>
</body>
</html>
