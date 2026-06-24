<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;

class EventAndNewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin?->id ?? 1;

        $this->command->info('📅 Membuat data acara...');
        $this->createEvents();

        $this->command->info('📰 Membuat data berita...');
        $this->createNews($adminId);

        $this->command->info('✅ Data acara dan berita berhasil dibuat!');
        $this->command->table(
            ['Jenis', 'Jumlah'],
            [
                ['Acara', Event::count()],
                ['Berita', News::count()],
            ]
        );
    }

    private function createEvents(): void
    {
        $events = [
            [
                'title'       => 'Job Fair SMK MUTU 2025',
                'type'        => 'job_fair',
                'description' => "Job Fair terbesar yang diselenggarakan oleh BKK SMK MUTU Cikampek!\n\nEvent ini menghadirkan lebih dari 20 perusahaan ternama dari berbagai industri yang siap merekrut lulusan SMK terbaik. Setiap peserta berkesempatan langsung mengirimkan CV dan mengikuti wawancara di tempat.\n\nApa yang bisa kamu dapatkan:\n- Bertemu langsung dengan HRD puluhan perusahaan\n- Wawancara langsung di hari yang sama\n- Informasi lowongan kerja terkini\n- Konsultasi karir gratis\n- Door prize menarik\n\nDaftarkan dirimu sekarang dan raih peluang karir impianmu!",
                'location'    => 'Aula Utama SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->addDays(14)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(14)->setHour(16)->setMinute(0),
            ],
            [
                'title'       => 'Seminar Karir: Persiapan Memasuki Dunia Kerja',
                'type'        => 'seminar',
                'description' => "Seminar karir spesial untuk siswa dan alumni SMK MUTU!\n\nNarasumber:\n- Bpk. Hendra Wijaya (HRD Manager PT Maju Bersama)\n- Ibu Rini Susanti (Career Coach berpengalaman 10 tahun)\n- Bpk. Dodi Pratama (Alumni sukses yang kini menjadi entrepreneur)\n\nMateri yang akan dibahas:\n1. Cara membuat CV yang menarik dan ATS-friendly\n2. Tips sukses wawancara kerja\n3. Etika profesional di dunia kerja\n4. Cara membangun personal branding di LinkedIn\n5. Sesi tanya jawab interaktif\n\nGratis untuk siswa dan alumni SMK MUTU. Tempat terbatas!",
                'location'    => 'Ruang Multimedia SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->addDays(7)->setHour(9)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(7)->setHour(12)->setMinute(0),
            ],
            [
                'title'       => 'Workshop Pembuatan CV & Portfolio Profesional',
                'type'        => 'workshop',
                'description' => "Workshop praktis membuat CV dan portfolio yang membuat HRD melirik lamaranmu!\n\nDalam workshop ini kamu akan belajar:\n- Struktur CV yang benar dan modern\n- Cara menulis pengalaman dan skill yang menarik\n- Membuat portfolio digital di Canva dan Notion\n- Optimasi profil LinkedIn untuk pencarian kerja\n- Praktik langsung membuat CV kamu sendiri\n\nFasilitas:\n✅ Modul materi lengkap\n✅ Template CV premium gratis\n✅ Sesi review CV personal\n✅ Sertifikat keikutsertaan\n\nPeserta wajib membawa laptop. Kuota terbatas 30 orang.",
                'location'    => 'Lab Komputer SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->addDays(21)->setHour(13)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(21)->setHour(17)->setMinute(0),
            ],
            [
                'title'       => 'Pelatihan Microsoft Office untuk Dunia Kerja',
                'type'        => 'pelatihan',
                'description' => "Kuasai Microsoft Office dan tingkatkan nilai jualmu di dunia kerja!\n\nMicrosoft Office (Word, Excel, PowerPoint) adalah skill wajib yang diminta hampir semua perusahaan. Dalam pelatihan intensif 2 hari ini, kamu akan:\n\nHari 1 - Microsoft Word & Excel:\n- Membuat surat resmi dan laporan profesional\n- Rumus Excel untuk administrasi dan keuangan\n- Pivot table dan visualisasi data\n\nHari 2 - PowerPoint & Praktik:\n- Desain presentasi yang menarik dan profesional\n- Simulasi tes Office untuk seleksi kerja\n- Studi kasus nyata dari dunia kerja\n\nSetelah lulus akan mendapat sertifikat dari BKK SMK MUTU.",
                'location'    => 'Lab Komputer SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->addDays(28)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(29)->setHour(16)->setMinute(0),
            ],
            [
                'title'       => 'Temu Alumni: Berbagi Pengalaman Karir',
                'type'        => 'seminar',
                'description' => "Acara spesial temu alumni SMK MUTU yang kini sudah sukses berkarir!\n\nDengarkan kisah inspiratif dari alumni yang telah berhasil membangun karir di berbagai bidang. Mereka akan berbagi:\n- Pengalaman pertama melamar kerja\n- Tantangan dan cara mengatasinya\n- Tips bertahan dan berkembang di perusahaan\n- Bagaimana ilmu SMK diterapkan di dunia nyata\n\nAcara ini terbuka untuk semua siswa, alumni, dan orang tua.\nAkan ada sesi networking dan konsultasi pribadi dengan alumni.",
                'location'    => 'Aula SMK MUTU Cikampek',
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
                'location'    => 'Aula SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->addDays(42)->setHour(9)->setMinute(0),
                'end_time'    => Carbon::now()->addDays(42)->setHour(15)->setMinute(0),
            ],
            // Acara yang sudah selesai (untuk tampilan historis)
            [
                'title'       => 'Pelatihan K3 (Keselamatan & Kesehatan Kerja)',
                'type'        => 'pelatihan',
                'description' => "Pelatihan wajib K3 untuk calon tenaga kerja industri.\n\nMemahami prosedur keselamatan kerja adalah kewajiban setiap tenaga kerja di industri. Pelatihan ini membekali peserta dengan:\n- Dasar-dasar K3 di tempat kerja\n- Penggunaan APD (Alat Pelindung Diri) yang benar\n- Prosedur darurat dan pertolongan pertama\n- Simulasi evakuasi\n\nPeserta mendapat sertifikat K3 yang diakui industri.",
                'location'    => 'Ruang Praktik SMK MUTU Cikampek',
                'start_time'  => Carbon::now()->subDays(10)->setHour(8)->setMinute(0),
                'end_time'    => Carbon::now()->subDays(10)->setHour(16)->setMinute(0),
            ],
        ];

        foreach ($events as $data) {
            Event::firstOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }

    private function createNews(int $adminId): void
    {
        $articles = [
            [
                'title'        => '10 Tips Sukses Wawancara Kerja untuk Fresh Graduate',
                'category'     => 'Tips Karir',
                'is_published' => true,
                'content'      => "<h2>10 Tips Sukses Wawancara Kerja untuk Fresh Graduate</h2>
<p>Wawancara kerja adalah tahap paling mendebarkan dalam proses pencarian kerja. Bagi fresh graduate yang belum berpengalaman, persiapan yang matang adalah kunci utama keberhasilan.</p>
<h3>1. Riset Perusahaan Sebelum Wawancara</h3>
<p>Pelajari profil perusahaan, produk/layanan, visi-misi, dan berita terbaru mereka. Pewawancara sangat terkesan dengan kandidat yang tahu banyak tentang perusahaan mereka.</p>
<h3>2. Persiapkan Jawaban untuk Pertanyaan Umum</h3>
<p>Latih jawaban untuk pertanyaan klasik seperti \"Ceritakan tentang diri Anda\", \"Apa kelebihan dan kekurangan Anda?\", dan \"Di mana Anda melihat diri Anda 5 tahun ke depan?\"</p>
<h3>3. Berpakaian Profesional dan Rapi</h3>
<p>Kesan pertama sangat penting. Kenakan pakaian formal atau smart casual yang bersih dan rapi. Hindari parfum yang terlalu menyengat.</p>
<h3>4. Datang 15 Menit Lebih Awal</h3>
<p>Keterlambatan adalah red flag besar bagi pewawancara. Datang lebih awal juga memberimu waktu untuk menenangkan diri.</p>
<h3>5. Bawa Dokumen Lengkap</h3>
<p>Siapkan CV, ijazah, transkrip nilai, sertifikat, dan foto dalam map yang rapi. Bawa beberapa rangkap untuk berjaga-jaga.</p>
<h3>6. Tunjukkan Antusiasme dan Semangat</h3>
<p>Perusahaan lebih suka kandidat yang antusias dan bersemangat belajar daripada yang pasif. Tunjukkan ketertarikanmu pada posisi dan perusahaan tersebut.</p>
<h3>7. Jujur tentang Kemampuan</h3>
<p>Jangan membesar-besarkan kemampuan yang tidak kamu miliki. Lebih baik jujur dan menunjukkan kemauan belajar yang tinggi.</p>
<h3>8. Ajukan Pertanyaan yang Cerdas</h3>
<p>Di akhir wawancara, ajukan pertanyaan seperti \"Bagaimana program pengembangan karyawan di perusahaan ini?\" Ini menunjukkan keseriusanmu.</p>
<h3>9. Jaga Bahasa Tubuh</h3>
<p>Duduk tegak, buat kontak mata, dan hindari menyilangkan tangan. Senyum yang tulus bisa membuat suasana lebih hangat.</p>
<h3>10. Follow Up Setelah Wawancara</h3>
<p>Kirim email ucapan terima kasih 24 jam setelah wawancara. Ini menunjukkan profesionalisme dan keseriusanmu.</p>
<p><strong>Semangat! Peluang kerja menanti mereka yang siap.</strong></p>",
            ],
            [
                'title'        => 'Panduan Membuat CV ATS-Friendly yang Lolos Seleksi Otomatis',
                'category'     => 'Tips Karir',
                'is_published' => true,
                'content'      => "<h2>Panduan Membuat CV ATS-Friendly</h2>
<p>ATS (Applicant Tracking System) adalah software yang digunakan perusahaan besar untuk menyaring CV secara otomatis sebelum sampai ke tangan HRD. Jika CV kamu tidak ATS-friendly, bisa langsung tersingkir meski kamu sangat qualified!</p>
<h3>Apa itu ATS?</h3>
<p>ATS adalah sistem yang memindai CV dan mencari kata kunci (keywords) yang relevan dengan posisi yang dilamar. CV yang tidak mengandung kata kunci yang tepat akan otomatis ditolak sistem.</p>
<h3>Format CV yang ATS-Friendly</h3>
<p>Gunakan format berikut agar CV bisa dibaca ATS dengan baik:</p>
<ul>
<li>Gunakan font standar: Arial, Calibri, atau Times New Roman ukuran 10-12pt</li>
<li>Simpan dalam format .docx atau .pdf</li>
<li>Hindari tabel, kolom, header/footer, dan grafik</li>
<li>Gunakan bullet points sederhana (•)</li>
<li>Urutan: Kontak → Ringkasan → Pengalaman → Pendidikan → Skill</li>
</ul>
<h3>Cara Memasukkan Keyword yang Tepat</h3>
<p>Baca deskripsi pekerjaan dengan teliti dan identifikasi kata kunci yang digunakan. Masukkan kata kunci tersebut secara natural ke dalam CV kamu, terutama di bagian:</p>
<ul>
<li>Professional summary</li>
<li>Deskripsi pengalaman kerja / magang</li>
<li>Bagian skill</li>
</ul>
<h3>Contoh Kata Kunci Populer per Bidang</h3>
<p><strong>IT/Teknologi:</strong> HTML, CSS, JavaScript, PHP, MySQL, Laravel, React, Git, REST API</p>
<p><strong>Akuntansi:</strong> Pembukuan, laporan keuangan, Excel, SAP, pajak, audit</p>
<p><strong>Teknik Mesin:</strong> AutoCAD, pemeliharaan mesin, K3, SOP, quality control</p>
<p>Gunakan fitur CV Builder di platform BKK SMK MUTU untuk membuat CV ATS-friendly secara otomatis!</p>",
            ],
            [
                'title'        => 'Daftar Lowongan Kerja Terbanyak di Kawasan Industri Karawang-Cikampek',
                'category'     => 'Info Lowongan',
                'is_published' => true,
                'content'      => "<h2>Peluang Kerja di Kawasan Industri Karawang-Cikampek</h2>
<p>Kawasan industri Karawang-Cikampek adalah salah satu pusat industri terbesar di Indonesia. Setiap tahun, ratusan perusahaan membuka lowongan untuk ribuan posisi, terutama bagi lulusan SMK.</p>
<h3>Industri dengan Lowongan Terbanyak</h3>
<h3>1. Industri Otomotif</h3>
<p>Karawang-Cikampek dikenal sebagai \"Detroit-nya Indonesia\" dengan hadirnya pabrik mobil dan komponen otomotif besar. Posisi yang paling banyak dibutuhkan:</p>
<ul>
<li>Operator produksi (SMK Teknik Mesin/Otomotif)</li>
<li>Quality Control Inspector</li>
<li>Teknisi maintenance</li>
<li>Staf gudang dan logistik</li>
</ul>
<h3>2. Industri Elektronik</h3>
<p>Banyak perusahaan elektronik Jepang, Korea, dan China yang memiliki pabrik di kawasan ini. Mereka membutuhkan:</p>
<ul>
<li>Operator perakitan elektronik (SMK Teknik Elektronika)</li>
<li>Teknisi peralatan produksi</li>
<li>Staf IT support pabrik</li>
</ul>
<h3>3. Industri Makanan & Minuman</h3>
<p>Beberapa brand FMCG ternama beroperasi di kawasan ini, membutuhkan lulusan SMK untuk:</p>
<ul>
<li>Operator produksi makanan</li>
<li>Quality assurance</li>
<li>Administrasi dan akuntansi</li>
</ul>
<h3>Tips Melamar ke Perusahaan di Kawasan Industri</h3>
<p>1. Daftarkan CV kamu di BKK SMK MUTU untuk mendapat referensi kerja.<br>
2. Ikuti job fair yang rutin diadakan setiap semester.<br>
3. Pantau terus info lowongan di platform ini.</p>
<p>Persiapkan dirimu dari sekarang dan manfaatkan semua fasilitas yang disediakan BKK SMK MUTU!</p>",
            ],
            [
                'title'        => 'Pentingnya Sertifikat Kompetensi untuk Meningkatkan Nilai Jual di Dunia Kerja',
                'category'     => 'Pengembangan Diri',
                'is_published' => true,
                'content'      => "<h2>Sertifikat Kompetensi: Investasi Terbaik untuk Karir Kamu</h2>
<p>Di era persaingan kerja yang semakin ketat, memiliki ijazah saja sudah tidak cukup. Sertifikat kompetensi bisa menjadi pembeda yang membuat lamaranmu lebih menonjol.</p>
<h3>Apa itu Sertifikat Kompetensi?</h3>
<p>Sertifikat kompetensi adalah bukti pengakuan formal atas kemampuan seseorang di bidang tertentu, yang dikeluarkan oleh lembaga sertifikasi resmi seperti BNSP (Badan Nasional Sertifikasi Profesi).</p>
<h3>Kenapa Sertifikat Penting?</h3>
<ul>
<li><strong>Bukti kemampuan nyata</strong> - Bukan sekadar nilai di rapor, tapi kemampuan yang telah diuji secara praktis</li>
<li><strong>Meningkatkan kepercayaan HRD</strong> - Perusahaan lebih percaya pada kandidat bersertifikat</li>
<li><strong>Gaji lebih tinggi</strong> - Kandidat bersertifikat umumnya mendapat penawaran gaji 15-30% lebih tinggi</li>
<li><strong>Membuka peluang internasional</strong> - Beberapa sertifikat diakui secara internasional</li>
</ul>
<h3>Sertifikat Populer per Jurusan SMK</h3>
<p><strong>Teknik Komputer & Jaringan:</strong> Cisco CCNA, CompTIA A+, MikroTik</p>
<p><strong>RPL/Pemrograman:</strong> Oracle Java, AWS Cloud Practitioner, Google IT Support</p>
<p><strong>Akuntansi:</strong> Brevet Pajak A&B, Sertifikasi MYOB/Accurate</p>
<p><strong>Teknik Mesin/Otomotif:</strong> Sertifikasi BNSP Teknisi Mesin, K3 Umum</p>
<h3>Cara Mendapatkan Sertifikat</h3>
<p>Gunakan fitur Sertifikat di platform BKK SMK MUTU untuk mencatat dan menampilkan sertifikat yang kamu miliki di profil lamaranmu!</p>",
            ],
            [
                'title'        => 'Kisah Sukses: Alumni SMK MUTU yang Kini Jadi Software Engineer',
                'category'     => 'Inspirasi',
                'is_published' => true,
                'content'      => "<h2>Dari Bangku SMK ke Software Engineer: Perjalanan Rizky</h2>
<p>Rizky Firmansyah, alumni jurusan Rekayasa Perangkat Lunak SMK MUTU angkatan 2022, kini bekerja sebagai Software Engineer di perusahaan startup teknologi di Jakarta dengan gaji yang kompetitif. Ini kisah perjalanannya.</p>
<h3>Awal yang Penuh Keraguan</h3>
<p>\"Dulu saya ragu, apakah lulusan SMK bisa bersaing dengan lulusan S1?\" cerita Rizky. \"Banyak yang meremehkan kami. Tapi saya membuktikan bahwa skill dan kerja keras lebih penting dari gelar.\"</p>
<h3>Masa SMK yang Produktif</h3>
<p>Selama di SMK MUTU, Rizky tidak hanya belajar di kelas. Ia aktif mengembangkan diri dengan:</p>
<ul>
<li>Belajar Laravel dan React secara mandiri di YouTube</li>
<li>Membuat 3 proyek pribadi yang diupload di GitHub</li>
<li>Mengikuti lomba coding tingkat kabupaten dan meraih juara 2</li>
<li>Aktif di komunitas developer lokal</li>
</ul>
<h3>Proses Melamar yang Panjang</h3>
<p>Rizky melamar ke 25 perusahaan sebelum akhirnya diterima. \"Jangan menyerah. Setiap penolakan mengajarkan saya hal baru,\" katanya.</p>
<h3>Pesan untuk Adik-Adik SMK</h3>
<p>\"Manfaatkan waktu di SMK sebaik-baiknya. Ikuti magang sungguh-sungguh, bangun portfolio dari sekarang, dan jangan takut untuk bermimpi besar. Kalian bisa!\"</p>
<p>BKK SMK MUTU bangga dengan pencapaian Rizky dan seluruh alumni kami yang telah membuktikan bahwa lulusan SMK bisa bersaing di tingkat nasional!</p>",
            ],
            [
                'title'        => 'Skill yang Paling Dicari Perusahaan di Tahun 2025',
                'category'     => 'Tren Industri',
                'is_published' => true,
                'content'      => "<h2>Skill Paling Dicari Perusahaan di 2025</h2>
<p>Dunia kerja terus berubah dengan cepat. Berdasarkan data dari berbagai job portal dan survei HRD, inilah skill yang paling dibutuhkan perusahaan di tahun 2025.</p>
<h3>Hard Skills Terpopuler</h3>
<h3>1. Literasi Digital dan Teknologi</h3>
<p>Hampir semua industri kini membutuhkan karyawan yang melek teknologi. Minimal harus bisa:</p>
<ul>
<li>Mengoperasikan komputer dan Microsoft Office dengan lancar</li>
<li>Menggunakan aplikasi berbasis cloud (Google Workspace, dll)</li>
<li>Memahami dasar-dasar keamanan siber</li>
</ul>
<h3>2. Analisis Data Dasar</h3>
<p>Kemampuan membaca dan menginterpretasikan data (menggunakan Excel, Google Sheets, atau Power BI) kini dicari di hampir semua divisi.</p>
<h3>3. Kecerdasan Buatan (AI) Literacy</h3>
<p>Memahami cara menggunakan tool AI seperti ChatGPT untuk meningkatkan produktivitas kerja menjadi nilai tambah besar.</p>
<h3>Soft Skills yang Tidak Tergantikan</h3>
<ul>
<li><strong>Komunikasi efektif</strong> - Kemampuan menyampaikan ide dengan jelas</li>
<li><strong>Adaptabilitas</strong> - Cepat menyesuaikan diri dengan perubahan</li>
<li><strong>Kerja sama tim</strong> - Kolaborasi lintas divisi</li>
<li><strong>Problem solving</strong> - Berpikir kritis dan mencari solusi</li>
<li><strong>Manajemen waktu</strong> - Produktif dan tepat deadline</li>
</ul>
<h3>Cara Mengembangkan Skill Kamu</h3>
<p>BKK SMK MUTU menyediakan berbagai program pelatihan dan workshop. Pantau terus jadwal acara kami untuk kesempatan belajar gratis!</p>",
            ],
            [
                'title'        => 'Cara Memanfaatkan Media Sosial untuk Mencari Kerja',
                'category'     => 'Tips Karir',
                'is_published' => true,
                'content'      => "<h2>Media Sosial sebagai Senjata Ampuh Mencari Kerja</h2>
<p>Di era digital ini, media sosial bukan hanya tempat berbagi foto liburan. Platform seperti LinkedIn, Instagram, dan bahkan TikTok bisa menjadi alat yang powerful untuk menemukan pekerjaan impianmu.</p>
<h3>LinkedIn: Platform Profesional #1</h3>
<p>LinkedIn adalah media sosial khusus profesional yang wajib kamu miliki. Tips mengoptimalkan LinkedIn:</p>
<ul>
<li>Gunakan foto profil profesional (bukan foto selfie)</li>
<li>Tulis headline yang menarik, bukan hanya \"Fresh Graduate\"</li>
<li>Isi bagian About dengan ringkasan diri yang menarik</li>
<li>Tambahkan semua pengalaman magang dan organisasi</li>
<li>Minta rekomendasi dari guru atau pembimbing PKL</li>
<li>Aktif posting konten terkait bidang keahlianmu</li>
</ul>
<h3>Instagram untuk Personal Branding</h3>
<p>Jika kamu di bidang kreatif (desain, fotografi, tata boga), gunakan Instagram sebagai portfolio visual. Posting karya terbaikmu secara konsisten.</p>
<h3>GitHub untuk Developer</h3>
<p>Bagi yang di bidang IT/pemrograman, GitHub adalah \"CV kedua\" kamu. Upload semua proyek yang pernah kamu buat, meski masih kecil.</p>
<h3>Yang Harus Dihindari di Media Sosial</h3>
<ul>
<li>Konten negatif, hoaks, atau kontroversial</li>
<li>Mengeluh soal pekerjaan atau atasan</li>
<li>Foto atau konten tidak profesional yang mudah ditemukan HRD</li>
</ul>
<p>Ingat: HRD sering memeriksa media sosial kandidat sebelum wawancara. Pastikan digital footprint kamu mencerminkan profesionalisme!</p>",
            ],
            [
                'title'        => 'Mengenal Hak dan Kewajiban Karyawan Baru: Yang Harus Kamu Tahu',
                'category'     => 'Info Ketenagakerjaan',
                'is_published' => true,
                'content'      => "<h2>Hak dan Kewajiban Karyawan Baru di Indonesia</h2>
<p>Selamat, kamu diterima kerja! Tapi sebelum mulai bekerja, ada hal-hal penting yang perlu kamu ketahui tentang hak dan kewajibanmu sebagai karyawan baru.</p>
<h3>Hak-Hak Karyawan yang Wajib Dipenuhi Perusahaan</h3>
<h3>1. Gaji dan Upah</h3>
<p>Perusahaan wajib membayar gaji minimal sesuai UMK (Upah Minimum Kabupaten/Kota) setempat. Untuk Kabupaten Karawang, UMK 2025 adalah salah satu yang tertinggi di Jawa Barat. Gaji harus dibayar paling lambat tanggal 10 bulan berikutnya.</p>
<h3>2. BPJS Kesehatan dan Ketenagakerjaan</h3>
<p>Perusahaan wajib mendaftarkan dan membayarkan iuran BPJS untuk karyawannya. Ini adalah kewajiban hukum, bukan kemurahan hati perusahaan!</p>
<h3>3. Cuti Tahunan</h3>
<p>Setelah bekerja selama 12 bulan berturut-turut, karyawan berhak atas 12 hari cuti berbayar per tahun.</p>
<h3>4. Tunjangan Hari Raya (THR)</h3>
<p>THR wajib dibayarkan minimal 7 hari sebelum Lebaran. Untuk karyawan yang sudah bekerja lebih dari 1 tahun, THR sebesar 1 bulan gaji.</p>
<h3>Kewajibanmu sebagai Karyawan Baru</h3>
<ul>
<li>Mematuhi peraturan dan SOP perusahaan</li>
<li>Hadir tepat waktu dan profesional</li>
<li>Menjaga rahasia perusahaan</li>
<li>Mengikuti masa percobaan (biasanya 3 bulan) dengan penuh tanggung jawab</li>
</ul>
<h3>Apa yang Harus Dilakukan Jika Hak Tidak Dipenuhi?</h3>
<p>Laporkan ke Dinas Ketenagakerjaan setempat atau konsultasikan dengan BKK SMK MUTU untuk mendapat panduan lebih lanjut.</p>",
            ],
        ];

        foreach ($articles as $data) {
            News::firstOrCreate(
                ['title' => $data['title']],
                array_merge($data, [
                    'user_id' => $adminId,
                    'slug'    => \Illuminate\Support\Str::slug($data['title']) . '-' . uniqid(),
                ])
            );
        }
    }
}
