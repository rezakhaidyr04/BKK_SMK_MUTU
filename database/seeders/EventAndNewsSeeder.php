<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventAndNewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📅 Membuat data acara...');
        $this->createEvents();

        $this->command->info('✅ Data acara berhasil dibuat!');
        $this->createNews();
        $this->command->table(
            ['Jenis', 'Jumlah'],
            [
                ['Acara', Event::count()],
                ['Acara gratis', Event::where('is_paid', false)->count()],
                ['Acara berbayar', Event::where('is_paid', true)->count()],
                ['Berita', News::count()],
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
            [
                'title'       => 'Bootcamp Intensif Persiapan Kerja dan Simulasi Rekrutmen',
                'type'        => 'workshop',
                'description' => "Bootcamp dua hari untuk pencari kerja yang ingin mempersiapkan CV, menghadapi psikotes, dan berlatih wawancara bersama praktisi HRD.\n\nPeserta mendapatkan:\n- Review CV ATS secara personal\n- Simulasi psikotes dan pembahasannya\n- Latihan wawancara dengan feedback\n- Modul digital dan lembar kerja\n- Sertifikat penyelesaian bootcamp\n\nKuota hanya 40 peserta agar setiap peserta mendapatkan sesi konsultasi yang cukup.",
                'location'    => 'Co-working Space Karawang, Jl. Galuh Mas Raya No. 10',
                'start_time'  => Carbon::now()->addDays(32)->setHour(8)->setMinute(30),
                'end_time'    => Carbon::now()->addDays(33)->setHour(16)->setMinute(0),
                'is_paid'     => true,
                'price'       => 125000,
                'quota'       => 40,
                'payment_instructions' => "Transfer ke BCA 1234567890 a.n. BKKMu SMK TI Muhammadiyah.\n\nPendaftaran:\n1. Isi formulir pendaftaran.\n2. Transfer biaya Rp 125.000.\n3. Upload bukti pembayaran pada halaman acara.\n4. Tunggu verifikasi admin maksimal 1x24 jam.\n\nBiaya sudah termasuk modul, konsumsi, dan sertifikat.",
            ],
        ];

        foreach ($events as $data) {
            $data['poster'] = $this->createEventPoster($data);
            Event::updateOrCreate(
                ['title' => $data['title']],
                array_merge([
                    'is_paid' => false,
                    'price' => null,
                    'quota' => null,
                    'payment_instructions' => null,
                ], $data)
            );
        }
    }

    private function createEventPoster(array $data): string
    {
        $path = 'event-posters/'.Str::slug($data['title']).'.svg';
        $title = htmlspecialchars($data['title'], ENT_XML1, 'UTF-8');
        $type = htmlspecialchars(ucwords(str_replace('_', ' ', $data['type'])), ENT_XML1, 'UTF-8');
        $date = Carbon::parse($data['start_time'])->format('d M Y');
        $time = Carbon::parse($data['start_time'])->format('H:i').' WIB';
        $price = ! empty($data['is_paid'])
            ? 'BERBAYAR - Rp '.number_format((float) $data['price'], 0, ',', '.')
            : 'GRATIS';

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="750" viewBox="0 0 1200 750">
    <defs>
        <linearGradient id="background" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="#0f3d91"/>
            <stop offset="100%" stop-color="#1687b8"/>
        </linearGradient>
        <pattern id="dots" width="32" height="32" patternUnits="userSpaceOnUse">
            <circle cx="4" cy="4" r="2" fill="#ffffff" opacity=".18"/>
        </pattern>
    </defs>
    <rect width="1200" height="750" fill="url(#background)"/>
    <rect width="1200" height="750" fill="url(#dots)"/>
    <circle cx="1080" cy="110" r="180" fill="#ffffff" opacity=".08"/>
    <circle cx="1150" cy="680" r="260" fill="#22d3ee" opacity=".12"/>
    <rect x="70" y="64" width="230" height="46" rx="23" fill="#facc15"/>
    <text x="185" y="95" text-anchor="middle" fill="#172554" font-family="Arial, sans-serif" font-size="20" font-weight="700">BKK SMK MUTU</text>
    <text x="70" y="190" fill="#bae6fd" font-family="Arial, sans-serif" font-size="25" font-weight="700" letter-spacing="3">ACARA KARIER</text>
    <text x="70" y="285" fill="#ffffff" font-family="Arial, sans-serif" font-size="54" font-weight="700">$title</text>
    <text x="70" y="335" fill="#dbeafe" font-family="Arial, sans-serif" font-size="28">$type</text>
    <rect x="70" y="430" width="440" height="90" rx="16" fill="#ffffff" opacity=".14"/>
    <text x="100" y="470" fill="#ffffff" font-family="Arial, sans-serif" font-size="23" font-weight="700">$date · $time</text>
    <text x="100" y="503" fill="#fef08a" font-family="Arial, sans-serif" font-size="23" font-weight="700">$price</text>
    <text x="70" y="670" fill="#e0f2fe" font-family="Arial, sans-serif" font-size="22">Daftar sekarang dan kembangkan kariermu.</text>
</svg>
SVG;

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function createNews(): void
    {
        $this->prepareNewsThumbnails();
        News::whereIn('title', [
            'Tips Membuat CV yang Menarik Perhatian HRD',
            'Persiapan Menghadapi Wawancara Kerja bagi Lulusan SMK',
            'Keterampilan Digital yang Banyak Dicari Dunia Kerja',
            'Peluang Karier Lulusan SMK di Industri Manufaktur',
            'Cara Membangun Pengalaman Kerja Sebelum Mendapat Pekerjaan Pertama',
        ])->delete();

        $news = [
            [
                'title' => 'Panduan Membuat CV ATS-Friendly yang Lolos Screening HRD',
                'category' => 'career_tips',
                'thumbnail' => 'news-thumbnails/cv-ats-friendly.jpg',
                'content' => "Banyak perusahaan menggunakan Applicant Tracking System (ATS) untuk menyaring CV sebelum dibaca oleh HRD. Sistem ini membaca teks, mengenali kata kunci, lalu mencocokkan pengalaman pelamar dengan kebutuhan posisi. Karena itu, CV yang menarik bukan hanya soal desain, tetapi juga soal struktur dan isi yang mudah dibaca mesin.\n\nAPA ITU CV ATS-FRIENDLY?\nCV ATS-friendly adalah CV dengan format sederhana, teks yang jelas, dan susunan informasi yang teratur. Hindari tabel yang rumit, kolom ganda, grafik kemampuan, ikon berlebihan, serta teks yang ditempatkan sebagai gambar karena informasi tersebut dapat gagal dibaca ATS.\n\nSTRUKTUR CV YANG DISARANKAN\n1. Nama lengkap dan kontak aktif: nomor telepon, email profesional, serta domisili.\n2. Ringkasan profesional: dua sampai empat kalimat yang menjelaskan keahlian utama dan target posisi.\n3. Pengalaman kerja atau organisasi: tulis jabatan, nama instansi, periode, tanggung jawab, dan hasil yang dicapai.\n4. Pendidikan: cantumkan sekolah, jurusan, dan tahun lulus.\n5. Keahlian: pilih kemampuan yang benar-benar dikuasai dan relevan dengan lowongan.\n6. Sertifikasi dan portofolio: sertakan jika mendukung posisi yang dilamar.\n\nCARA MENULIS PENGALAMAN AGAR LEBIH KUAT\nJangan hanya menulis bahwa kamu bertugas sebagai admin atau operator. Jelaskan kontribusinya dengan pola tindakan, pekerjaan, dan hasil. Contohnya: Mengelola rekap stok mingguan menggunakan Excel sehingga laporan lebih cepat diserahkan kepada supervisor. Gunakan angka jika tersedia, seperti jumlah anggota tim, target, peserta, atau dokumen yang dikelola.\n\nCHECKLIST SEBELUM MENGIRIM CV\nPastikan judul file profesional, misalnya CV_Ahmad_Nur_Ajril.pdf. Gunakan kata kunci dari lowongan secara wajar, periksa ejaan, gunakan email yang sopan, dan simpan CV dalam format PDF. Sesuaikan CV untuk setiap posisi, jangan mengirim satu CV yang sama ke semua perusahaan.",
            ],
            [
                'title' => 'Strategi Wawancara Kerja: Menjawab dengan STAR dan Percaya Diri',
                'category' => 'career_tips',
                'thumbnail' => 'news-thumbnails/interview-preparation.webp',
                'content' => "Wawancara kerja adalah kesempatan untuk membuktikan bahwa pengalaman dan cara berpikirmu sesuai dengan kebutuhan perusahaan. Persiapan yang baik membuat jawaban lebih terarah dan membantu kamu tampil tenang, terutama saat ditanya tentang pengalaman yang belum terlalu banyak.\n\nSEBELUM WAWANCARA\nPelajari profil perusahaan, produk atau layanannya, lokasi kerja, dan isi lowongan secara menyeluruh. Tandai persyaratan yang paling penting lalu siapkan contoh pengalaman yang relevan dari pekerjaan, magang, organisasi, kepanitiaan, atau kegiatan sosial.\n\nGUNAKAN METODE STAR\nSaat menjawab pertanyaan pengalaman, gunakan empat bagian: Situation untuk menjelaskan situasi, Task untuk menjelaskan tanggung jawab, Action untuk menjelaskan tindakanmu, dan Result untuk menjelaskan hasilnya. Contohnya, ketika ditanya tentang kepemimpinan, ceritakan masalah yang dihadapi tim, peranmu, keputusan yang diambil, dan perubahan yang berhasil dicapai.\n\nPERTANYAAN YANG PERLU DILATIH\nSiapkan jawaban untuk pertanyaan tentang perkenalan diri, alasan melamar, kelebihan dan kekurangan, pengalaman menghadapi konflik, kesiapan bekerja dalam tim, serta rencana pengembangan diri. Jangan menghafalkan jawaban kata demi kata; pahami poin utamanya agar tetap terdengar natural.\n\nETIKA SAAT WAWANCARA\nDatang 10 sampai 15 menit lebih awal, berpakaian rapi, matikan notifikasi telepon, dengarkan pertanyaan sampai selesai, dan jawab dengan jujur. Di akhir wawancara, tanyakan tahapan seleksi berikutnya dan ucapkan terima kasih kepada pewawancara.",
            ],
            [
                'title' => 'Skill Digital yang Wajib Dikuasai untuk Memulai Karier',
                'category' => 'work_world',
                'thumbnail' => 'news-thumbnails/digital-skills.webp',
                'content' => "Dunia kerja tidak hanya membutuhkan lulusan yang memahami teori. Perusahaan juga mencari orang yang mampu menggunakan perangkat digital untuk menyelesaikan pekerjaan secara cepat, rapi, dan aman. Keterampilan ini bisa dibangun melalui latihan sederhana dan proyek nyata.\n\nKETERAMPILAN DASAR YANG BERGUNA\nMicrosoft Word membantu membuat surat dan laporan profesional. Excel digunakan untuk rekap data, rumus dasar, filter, dan laporan sederhana. PowerPoint membantu menyampaikan ide dengan struktur yang jelas. Selain itu, kemampuan mengelola email, kalender, penyimpanan cloud, dan rapat daring sudah menjadi bagian dari pekerjaan sehari-hari.\n\nKEMAMPUAN YANG MEMBUATMU LEBIH SIAP KERJA\nPelajari cara membuat spreadsheet dengan validasi data, menggunakan rumus SUMIF dan COUNTIF, menyusun folder kerja, serta menjaga penamaan file agar konsisten. Untuk jurusan teknologi, bangun portofolio berupa website, aplikasi sederhana, desain, dokumentasi jaringan, atau analisis data.\n\nJANGAN LUPA KETERAMPILAN NONTEKNIS\nKemampuan digital harus disertai komunikasi tertulis, ketelitian, pemecahan masalah, manajemen waktu, dan kemauan belajar. Perusahaan menghargai pelamar yang dapat menjelaskan proses kerja, mencatat perubahan, dan meminta bantuan dengan cara yang tepat.\n\nMULAI DARI PROYEK KECIL\nBuat satu proyek yang dapat ditunjukkan dalam CV, misalnya dashboard penjualan sederhana, template administrasi, desain promosi, atau website profil usaha. Simpan hasilnya dengan rapi dan tuliskan masalah apa yang diselesaikan oleh proyek tersebut.",
            ],
            [
                'title' => 'Mengenal Jalur Karier Lulusan SMK di Industri Manufaktur',
                'category' => 'industry_info',
                'thumbnail' => 'news-thumbnails/manufacturing-career.jpeg',
                'content' => "Kawasan industri Karawang, Bekasi, dan Subang membuka banyak peluang bagi lulusan SMK. Kebutuhan tenaga kerja tidak hanya berada di lini produksi, tetapi juga pada pemeliharaan mesin, pengendalian mutu, pergudangan, logistik, dan administrasi.\n\nPILIHAN POSISI YANG UMUM TERSEDIA\nLulusan Teknik Mesin dan Otomotif dapat mempertimbangkan posisi operator produksi, teknisi maintenance, tooling, atau quality control. Lulusan Teknik Elektronika dan Listrik dapat mengembangkan karier pada perakitan, panel, instrumentasi, dan perawatan kelistrikan. Lulusan TKJ atau RPL dapat melamar sebagai IT support, administrator sistem, atau staf pengolahan data. Lulusan administrasi dapat bekerja pada production control, purchasing, warehouse, dan administrasi HR.\n\nKOMPETENSI YANG DINILAI PERUSAHAAN\nSelain keterampilan teknis, perusahaan memperhatikan pemahaman K3, kedisiplinan, kesiapan kerja shift, ketelitian membaca instruksi, dan kemampuan bekerja dalam tim. Kandidat yang mampu membuat laporan sederhana dan berkomunikasi dengan supervisor biasanya lebih mudah beradaptasi.\n\nCARA MEMPERKUAT PROFIL\nCantumkan pengalaman praktik kerja industri secara spesifik. Jelaskan mesin, alat, aplikasi, atau prosedur yang pernah digunakan. Sertifikat K3, pengalaman quality control, dan kemampuan membaca gambar teknik juga dapat menjadi nilai tambah. Ikuti lowongan melalui kanal resmi dan pastikan tidak ada permintaan biaya rekrutmen.",
            ],
            [
                'title' => 'Cara Mengubah Pengalaman Organisasi Menjadi Nilai Jual di CV',
                'category' => 'work_world',
                'thumbnail' => 'news-thumbnails/organization-experience.webp',
                'content' => "Banyak pelamar memiliki pengalaman memimpin kegiatan, mengelola administrasi, membantu koperasi, atau menjadi panitia, tetapi belum menuliskannya dengan cara yang meyakinkan. Pengalaman tersebut dapat menjadi bukti kemampuan kerja apabila dijelaskan dengan tanggung jawab dan hasil yang konkret.\n\nTULIS PERAN, BUKAN HANYA JABATAN\nJangan berhenti pada kalimat Ketua Panitia atau Pengurus Organisasi. Jelaskan apa yang kamu kelola, siapa yang kamu koordinasikan, dan keputusan apa yang kamu ambil. Contoh: Memimpin tim 12 orang untuk menyelenggarakan kegiatan sosial, mengatur jadwal relawan, serta memastikan distribusi bantuan kepada 150 penerima.\n\nGUNAKAN HASIL YANG TERUKUR\nMasukkan angka jika tersedia, seperti jumlah anggota, peserta, anggaran, kegiatan, dokumen, atau target yang dicapai. Angka membantu HRD memahami skala tanggung jawab. Jika tidak ada angka, jelaskan perubahan yang dihasilkan, seperti proses menjadi lebih tertib atau pelayanan menjadi lebih cepat.\n\nHUBUNGKAN DENGAN POSISI YANG DILAMAR\nPengalaman sebagai manajer program dapat menunjukkan perencanaan dan koordinasi. Pengalaman mengelola koperasi dapat menunjukkan administrasi, pelayanan, dan pengelolaan operasional. Pengalaman memimpin organisasi dapat menunjukkan komunikasi, negosiasi, dan pengambilan keputusan. Pilih pengalaman yang paling dekat dengan kebutuhan lowongan.\n\nJAGA KEJUJURAN DAN SIAPKAN BUKTI\nTulis pengalaman secara jujur dan gunakan istilah yang mudah dipahami. Siapkan surat keterangan, portofolio, atau kontak referensi jika diminta. Profil yang kuat bukan profil yang paling banyak klaimnya, melainkan profil yang dapat menjelaskan kontribusi dengan jelas.",
            ],
        ];

        foreach ($news as $data) {
            News::updateOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'is_published' => true,
                    'published_at' => now(),
                ])
            );
        }

        $this->command->info('Berita dummy berhasil dibuat/diperbarui.');
    }

    private function prepareNewsThumbnails(): void
    {
        $sources = [
            'news-thumbnails/cv-ats-friendly.jpg' => storage_path('app/public/news-thumbnails/cv-ats-friendly.jpg'),
            'news-thumbnails/interview-preparation.webp' => public_path('images/foto_siswa/siswa.webp'),
            'news-thumbnails/digital-skills.webp' => public_path('images/foto_siswa/siswa.webp'),
            'news-thumbnails/manufacturing-career.jpg' => public_path('images/perusahaan/perusahaan.jpeg'),
            'news-thumbnails/organization-experience.jpg' => public_path('images/foto_siswa/siswa.webp'),
        ];

        foreach ($sources as $target => $source) {
            if (Storage::disk('public')->exists($target)) {
                continue;
            }

            if (! is_file($source)) {
                $this->command->warn("Thumbnail berita tidak ditemukan: {$source}");
                continue;
            }

            Storage::disk('public')->put($target, file_get_contents($source));
        }
    }
}
