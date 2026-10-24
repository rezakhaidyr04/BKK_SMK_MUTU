<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventAndNewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📅 Membuat data acara...');
        $this->createEvents();

        $this->command->info('✅ Data acara berhasil dibuat!');
        $this->command->table(
            ['Jenis', 'Jumlah'],
            [
                ['Acara', Event::count()],
            ]
        );
    }

    private function createEvents(): void
    {
        $events = [
            [
                'title'       => 'Job Fair SMK TI Muhammadiyah Cikampek 2025',
                'type'        => 'job_fair',
                'description' => "Job Fair terbesar yang diselenggarakan oleh BKKMu Cikampek!\n\nEvent ini menghadirkan lebih dari 20 perusahaan ternama dari berbagai industri yang siap merekrut lulusan SMK terbaik. Setiap peserta berkesempatan langsung mengirimkan CV dan mengikuti wawancara di tempat.\n\nApa yang bisa kamu dapatkan:\n- Bertemu langsung dengan HRD puluhan perusahaan\n- Wawancara langsung di hari yang sama\n- Informasi lowongan kerja terkini\n- Konsultasi karir gratis\n- Door prize menarik\n\nDaftarkan dirimu sekarang dan raih peluang karir impianmu!",
                'location'    => 'Aula Utama SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(14)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(14)->setHour(16)->setMinute(0),
            ],
            [
                'title'       => 'Seminar Karir: Persiapan Memasuki Dunia Kerja',
                'type'        => 'seminar',
                'description' => "Seminar karir spesial untuk siswa dan alumni SMK TI Muhammadiyah Cikampek!\n\nNarasumber:\n- Bpk. Hendra Wijaya (HRD Manager PT Maju Bersama)\n- Ibu Rini Susanti (Career Coach berpengalaman 10 tahun)\n- Bpk. Dodi Pratama (Alumni sukses yang kini menjadi entrepreneur)\n\nMateri yang akan dibahas:\n1. Cara membuat CV yang menarik dan ATS-friendly\n2. Tips sukses wawancara kerja\n3. Etika profesional di dunia kerja\n4. Cara membangun personal branding di LinkedIn\n5. Sesi tanya jawab interaktif\n\nGratis untuk siswa dan alumni SMK TI Muhammadiyah Cikampek. Tempat terbatas!",
                'location'    => 'Ruang Multimedia SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(7)->setHour(9)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(7)->setHour(12)->setMinute(0),
            ],
            [
                'title'       => 'Workshop Pembuatan CV & Portfolio Profesional',
                'type'        => 'workshop',
                'description' => "Workshop praktis membuat CV dan portfolio yang membuat HRD melirik lamaranmu!\n\nDalam workshop ini kamu akan belajar:\n- Struktur CV yang benar dan modern\n- Cara menulis pengalaman dan skill yang menarik\n- Membuat portfolio digital di Canva dan Notion\n- Optimasi profil LinkedIn untuk pencarian kerja\n- Praktik langsung membuat CV kamu sendiri\n\nFasilitas:\n✅ Modul materi lengkap\n✅ Template CV premium gratis\n✅ Sesi review CV personal\n✅ Sertifikat keikutsertaan\n\nPeserta wajib membawa laptop. Kuota terbatas 30 orang.",
                'location'    => 'Lab Komputer SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(21)->setHour(13)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(21)->setHour(17)->setMinute(0),
            ],
            [
                'title'       => 'Pelatihan Microsoft Office untuk Dunia Kerja',
                'type'        => 'pelatihan',
                'description' => "Kuasai Microsoft Office dan tingkatkan nilai jualmu di dunia kerja!\n\nMicrosoft Office (Word, Excel, PowerPoint) adalah skill wajib yang diminta hampir semua perusahaan. Dalam pelatihan intensif 2 hari ini, kamu akan:\n\nHari 1 - Microsoft Word & Excel:\n- Membuat surat resmi dan laporan profesional\n- Rumus Excel untuk administrasi dan keuangan\n- Pivot table dan visualisasi data\n\nHari 2 - PowerPoint & Praktik:\n- Desain presentasi yang menarik dan profesional\n- Simulasi tes Office untuk seleksi kerja\n- Studi kasus nyata dari dunia kerja\n\nSetelah lulus akan mendapat sertifikat dari BKKMu.",
                'location'    => 'Lab Komputer SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(28)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(29)->setHour(16)->setMinute(0),
            ],
            [
                'title'       => 'Temu Alumni: Berbagi Pengalaman Karir',
                'type'        => 'seminar',
                'description' => "Acara spesial temu alumni SMK TI Muhammadiyah Cikampek yang kini sudah sukses berkarir!\n\nDengarkan kisah inspiratif dari alumni yang telah berhasil membangun karir di berbagai bidang. Mereka akan berbagi:\n- Pengalaman pertama melamar kerja\n- Tantangan dan cara mengatasinya\n- Tips bertahan dan berkembang di perusahaan\n- Bagaimana ilmu SMK diterapkan di dunia nyata\n\nAcara ini terbuka untuk semua siswa, alumni, dan orang tua.\nAkan ada sesi networking dan konsultasi pribadi dengan alumni.",
                'location'    => 'Aula SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(35)->setHour(9)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(35)->setHour(13)->setMinute(0),
            ],
            [
                'title'       => 'Job Fair Industri Manufaktur Karawang',
                'type'        => 'job_fair',
                'description' => "Kesempatan emas bagi lulusan SMK jurusan teknik!\n\nJob fair khusus industri manufaktur dan otomotif ini diikuti oleh lebih dari 15 pabrik besar di kawasan industri Karawang dan Cikampek.\n\nPerusahaan yang hadir antara lain dari industri:\n🏭 Otomotif dan komponen kendaraan\n⚙️ Elektronik dan peralatan rumah tangga\n🔧 Manufaktur logam dan plastik\n🏗️ Konstruksi dan properti\n\nPosisi yang tersedia:\n- Operator produksi\n- Teknisi mesin\n- Quality control\n- Gudang dan logistik\n\nBawa CV terbaru, foto formal, dan ijazah (asli + fotokopi).",
                'location'    => 'GOR Karawang Convention Center',
                'start_time'  => Carbon::now()->addDays(45)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(45)->setHour(17)->setMinute(0),
            ],
            [
                'title'       => 'Workshop Kewirausahaan untuk Generasi Muda',
                'type'        => 'workshop',
                'description' => "Tidak hanya jadi karyawan, kamu juga bisa jadi pengusaha sukses!\n\nWorkshop kewirausahaan ini dirancang khusus untuk siswa dan alumni SMK yang tertarik memulai usaha sendiri.\n\nMateri:\n1. Ide bisnis dari hobi dan keahlian SMK\n2. Cara membuat rencana bisnis sederhana\n3. Modal usaha: cara mendapatkan modal awal\n4. Digital marketing untuk UMKM\n5. Kisah sukses pengusaha muda asal Cikampek\n\nPembicara: Ibu Fitri Handayani - Founder UMKM Batik Cikampek yang kini sudah ekspor ke 5 negara.\n\nGratis! Daftar sekarang sebelum penuh.",
                'location'    => 'Aula SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->addDays(42)->setHour(9)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(42)->setHour(15)->setMinute(0),
            ],
            // Acara yang sudah selesai (untuk tampilan historis)
            [
                'title'       => 'Pelatihan K3 (Keselamatan & Kesehatan Kerja)',
                'type'        => 'pelatihan',
                'description' => "Pelatihan wajib K3 untuk calon tenaga kerja industri.\n\nMemahami prosedur keselamatan kerja adalah kewajiban setiap tenaga kerja di industri. Pelatihan ini membekali peserta dengan:\n- Dasar-dasar K3 di tempat kerja\n- Penggunaan APD (Alat Pelindung Diri) yang benar\n- Prosedur darurat dan pertolongan pertama\n- Simulasi evakuasi\n\nPeserta mendapat sertifikat K3 yang diakui industri.",
                'location'    => 'Ruang Praktik SMK TI Muhammadiyah Cikampek Cikampek',
                'start_time'  => Carbon::now()->subDays(10)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->subDays(10)->setHour(16)->setMinute(0),
            ],
            // ✅ ACARA BERBAYAR
            [
                'title'       => 'Job Fair Premium Industri Teknologi dan Startup 2025',
                'type'        => 'job_fair',
                'description' => "Job Fair eksklusif khusus sektor teknologi dan startup terkemuka!\n\nEvent premium ini menghadirkan lebih dari 25 perusahaan teknologi bergengsi yang siap merekrut talenta terbaik.\n\nFasilitas peserta:\n- Akses lounge eksklusif peserta terdaftar\n- Sesi speed interview langsung dengan HRD\n- Konsultasi karir 1-on-1 bersama career coach profesional\n- Makan siang dan snack selama event\n- Sertifikat partisipasi resmi\n- Goodie bag eksklusif dari sponsor\n\nPerusahaan yang hadir:\n- Startup teknologi Series A dan B\n- Perusahaan SaaS lokal dan multinasional\n- Fintech dan e-commerce terkemuka\n- Perusahaan outsourcing IT\n\nPosisi yang dibuka:\n- Software Developer (Frontend, Backend, Fullstack)\n- UI/UX Designer\n- Data Analyst dan Data Scientist\n- Digital Marketing Specialist\n- Quality Assurance Engineer\n- IT Support dan Network Engineer\n\nKuota terbatas hanya 100 peserta. Daftar segera sebelum kehabisan!",
                'location'    => 'Hotel Grand Cikampek, Jl. Ahmad Yani No. 88, Cikampek',
                'start_time'  => Carbon::now()->addDays(20)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(20)->setHour(17)->setMinute(0),
                'is_paid'     => true,
                'price'       => 75000,
                'quota'       => 100,
                'payment_instructions' => "Pembayaran dapat dilakukan melalui:\n\nTransfer Bank:\n- BCA  : 1234567890 a.n. BKKMu SMK TI Muhammadiyah\n- BRI  : 0987654321 a.n. BKKMu SMK TI Muhammadiyah\n- Mandiri: 1122334455 a.n. BKKMu SMK TI Muhammadiyah\n\nE-Wallet (GoPay / OVO / DANA): 081234567890\n\nTata Cara Pembayaran:\n1. Transfer sesuai nominal Rp 75.000\n2. Simpan bukti transfer / screenshot\n3. Upload bukti bayar di halaman pendaftaran acara\n4. Tunggu konfirmasi dari admin (maks. 1x24 jam kerja)\n5. Setelah dikonfirmasi, tiket digital dikirim ke email kamu\n\nInfo lebih lanjut hubungi WhatsApp: 081234567890",
            ],
        ];

        foreach ($events as $data) {
            Event::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }

}
