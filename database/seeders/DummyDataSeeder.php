<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Student;
use App\Models\Job;
use App\Models\Application;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏭 Membuat data perusahaan...');
        $companies = $this->createCompanies();

        $this->command->info('📋 Membuat lowongan kerja...');
        $jobs = $this->createJobs($companies);

        $this->command->info('🎓 Membuat akun siswa...');
        $students = $this->createStudents();

        $this->command->info('📨 Membuat lamaran...');
        $this->createApplications($students, $jobs);

        $this->command->info('🔖 Membuat bookmark...');
        $this->createBookmarks($students, $jobs);

        $this->command->newLine();
        $this->command->info('✅ Semua data dummy berhasil dibuat!');
        $this->command->table(
            ['Jenis Data', 'Jumlah'],
            [
                ['Perusahaan', count($companies)],
                ['Lowongan', count($jobs)],
                ['Siswa', count($students)],
                ['Lamaran', Application::count()],
            ]
        );
    }

    // ─── PERUSAHAAN ───────────────────────────────────────────────
    private function createCompanies(): array
    {
        $data = [
            [
                'email' => 'pt.maju@bkk.com',
                'name'  => 'PT Maju Bersama',
                'industry' => 'Manufaktur',
                'description' => 'Perusahaan manufaktur terkemuka di Cikampek yang bergerak di bidang otomotif dan elektronik.',
                'website' => 'https://ptmajubersama.com',
                'address' => 'Jl. Industri Raya No. 12, Cikampek Barat',
            ],
            [
                'email' => 'pt.tekno@bkk.com',
                'name'  => 'PT Teknologi Nusantara',
                'industry' => 'Teknologi Informasi',
                'description' => 'Perusahaan IT yang fokus pada pengembangan software dan solusi digital untuk UMKM Indonesia.',
                'website' => 'https://teknonusantara.id',
                'address' => 'Jl. Sudirman No. 45, Cikampek Timur',
            ],
            [
                'email' => 'pt.retail@bkk.com',
                'name'  => 'PT Ritel Cikampek',
                'industry' => 'Perdagangan & Ritel',
                'description' => 'Jaringan supermarket dan minimarket modern yang tersebar di seluruh Cikampek dan sekitarnya.',
                'website' => 'https://ritelCikampek.co.id',
                'address' => 'Jl. Tuparev No. 78, Cikampek',
            ],
            [
                'email' => 'pt.logistik@bkk.com',
                'name'  => 'PT Cepat Kirim Logistik',
                'industry' => 'Logistik & Transportasi',
                'description' => 'Perusahaan logistik dan pengiriman barang dengan armada lengkap mencakup seluruh Jawa Barat.',
                'website' => 'https://cepatkirim.id',
                'address' => 'Jl. Bypass Tanjungpura No. 5, Cikampek',
            ],
            [
                'email' => 'pt.hotel@bkk.com',
                'name'  => 'PT Cikampek Hospitality',
                'industry' => 'Perhotelan & Pariwisata',
                'description' => 'Pengelola jaringan hotel bintang tiga di kawasan industri Cikampek dengan standar pelayanan internasional.',
                'website' => 'https://Cikampekhospitality.com',
                'address' => 'Jl. Arteri Tol No. 99, Cikampek',
            ],
        ];

        $companies = [];
        foreach ($data as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name'              => $d['name'],
                    'password'          => Hash::make('password123'),
                    'role'              => 'company',
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['company']);

            $company = Company::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'                => $d['name'],
                    'industry'            => $d['industry'],
                    'description'         => $d['description'],
                    'website'             => $d['website'],
                    'address'             => $d['address'],
                    'is_verified'         => true,
                    'verification_status' => 'verified',
                ]
            );

            $companies[] = $company;
        }

        return $companies;
    }

    // ─── LOWONGAN ─────────────────────────────────────────────────
    private function createJobs(array $companies): array
    {
        $jobsData = [
            // PT Maju Bersama
            [
                'company_index' => 0,
                'title'       => 'Operator Produksi',
                'position'    => 'Operator',
                'location'    => 'Cikampek Barat',
                'job_type'    => 'full_time',
                'salary_min'  => 3500000,
                'salary_max'  => 4500000,
                'description' => "Kami mencari operator produksi yang teliti dan berdedikasi untuk bergabung di lini produksi kami.\n\nTanggung jawab:\n- Mengoperasikan mesin produksi sesuai SOP\n- Menjaga kualitas produk sesuai standar\n- Melaporkan kerusakan mesin kepada supervisor\n- Menjaga kebersihan area kerja",
                'qualifications' => "- Lulusan SMK jurusan Teknik Mesin/Otomotif/Elektronika\n- Usia maksimal 25 tahun\n- Bersedia bekerja shift\n- Sehat jasmani dan rohani\n- Teliti dan bertanggung jawab",
                'benefits'    => "- Gaji pokok + uang makan + uang transport\n- BPJS Kesehatan dan Ketenagakerjaan\n- Tunjangan hari raya\n- Seragam kerja\n- Bonus produksi",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(30),
            ],
            [
                'company_index' => 0,
                'title'       => 'Teknisi Mesin',
                'position'    => 'Teknisi',
                'location'    => 'Cikampek Barat',
                'job_type'    => 'full_time',
                'salary_min'  => 4000000,
                'salary_max'  => 5500000,
                'description' => "Dibutuhkan teknisi mesin berpengalaman untuk perawatan dan perbaikan mesin produksi.\n\nTanggung jawab:\n- Melakukan perawatan preventif mesin\n- Troubleshooting kerusakan mesin\n- Membuat laporan maintenance\n- Berkoordinasi dengan tim produksi",
                'qualifications' => "- Lulusan SMK Teknik Mesin/Elektro\n- Pengalaman min. 1 tahun (fresh graduate dipersilakan)\n- Memahami gambar teknik\n- Menguasai dasar-dasar pneumatik dan hidrolik",
                'benefits'    => "- Gaji kompetitif\n- BPJS lengkap\n- Pelatihan dan sertifikasi\n- Jenjang karir jelas",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(25),
            ],

            // PT Teknologi Nusantara
            [
                'company_index' => 1,
                'title'       => 'Junior Web Developer',
                'position'    => 'Developer',
                'location'    => 'Cikampek Timur',
                'job_type'    => 'full_time',
                'salary_min'  => 3000000,
                'salary_max'  => 5000000,
                'description' => "Bergabunglah dengan tim developer kami untuk membangun aplikasi web modern.\n\nTanggung jawab:\n- Mengembangkan fitur front-end dan back-end\n- Berkolaborasi dengan tim desainer dan PM\n- Menulis kode yang bersih dan terdokumentasi\n- Mengikuti code review dan daily standup",
                'qualifications' => "- Lulusan SMK/D3 Rekayasa Perangkat Lunak / TKJ\n- Menguasai HTML, CSS, JavaScript\n- Nilai tambah: mengenal PHP/Laravel atau React\n- Mau belajar dan berkembang\n- Bisa bekerja dalam tim",
                'benefits'    => "- Gaji + tunjangan internet\n- Laptop kerja disediakan\n- Lingkungan kerja modern\n- Remote/hybrid working\n- Pelatihan rutin",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(45),
            ],
            [
                'company_index' => 1,
                'title'       => 'Staf IT Support',
                'position'    => 'IT Support',
                'location'    => 'Cikampek Timur',
                'job_type'    => 'full_time',
                'salary_min'  => 2800000,
                'salary_max'  => 3800000,
                'description' => "Kami membutuhkan staf IT Support untuk membantu operasional teknis kantor dan klien.\n\nTanggung jawab:\n- Instalasi dan konfigurasi hardware/software\n- Menangani helpdesk pengguna\n- Maintenance jaringan kantor\n- Dokumentasi aset IT",
                'qualifications' => "- Lulusan SMK TKJ / Rekayasa Perangkat Lunak\n- Memahami jaringan dasar (LAN, WiFi, router)\n- Bisa troubleshooting Windows & Office\n- Komunikatif dan sabar",
                'benefits'    => "- Gaji pokok + transport\n- BPJS Kesehatan\n- Sertifikasi IT ditanggung perusahaan",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(20),
            ],

            // PT Ritel Cikampek
            [
                'company_index' => 2,
                'title'       => 'Kasir Supermarket',
                'position'    => 'Kasir',
                'location'    => 'Cikampek',
                'job_type'    => 'full_time',
                'salary_min'  => 2700000,
                'salary_max'  => 3200000,
                'description' => "Dicari kasir yang ramah dan teliti untuk supermarket kami yang ramai.\n\nTanggung jawab:\n- Melayani transaksi pembayaran pelanggan\n- Mengelola kas dengan akurat\n- Memberikan pelayanan prima kepada pelanggan\n- Menjaga kebersihan area kasir",
                'qualifications' => "- Lulusan SMK Akuntansi / Bisnis / Administrasi\n- Penampilan menarik dan ramah\n- Teliti dan jujur\n- Bersedia kerja shift dan weekend",
                'benefits'    => "- Gaji UMK Cikampek\n- Uang makan harian\n- BPJS Kesehatan & Ketenagakerjaan\n- Diskon belanja karyawan 10%",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(15),
            ],
            [
                'company_index' => 2,
                'title'       => 'Staf Administrasi',
                'position'    => 'Admin',
                'location'    => 'Cikampek',
                'job_type'    => 'full_time',
                'salary_min'  => 2800000,
                'salary_max'  => 3500000,
                'description' => "Dibutuhkan staf administrasi untuk mendukung operasional toko.\n\nTanggung jawab:\n- Input data dan pengarsipan dokumen\n- Membuat laporan harian/mingguan\n- Mengelola jadwal dan korespondensi\n- Berkoordinasi antar departemen",
                'qualifications' => "- Lulusan SMK Administrasi Perkantoran / Akuntansi\n- Menguasai Microsoft Office (Word, Excel, PowerPoint)\n- Teliti, rapi, dan terorganisir\n- Komunikasi baik",
                'benefits'    => "- Gaji + tunjangan\n- BPJS\n- THR dan bonus tahunan",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(35),
            ],

            // PT Cepat Kirim Logistik
            [
                'company_index' => 3,
                'title'       => 'Kurir Pengiriman',
                'position'    => 'Kurir',
                'location'    => 'Cikampek & Sekitarnya',
                'job_type'    => 'full_time',
                'salary_min'  => 3000000,
                'salary_max'  => 4000000,
                'description' => "Bergabunglah sebagai kurir pengiriman dan bantu ribuan pelanggan menerima paket mereka tepat waktu.\n\nTanggung jawab:\n- Mengantarkan paket ke alamat tujuan\n- Memastikan kondisi paket tetap baik\n- Konfirmasi pengiriman via aplikasi\n- Melaporkan kendala di lapangan",
                'qualifications' => "- Lulusan SMA/SMK sederajat\n- Memiliki SIM C aktif\n- Mengenal wilayah Cikampek\n- Jujur, disiplin, dan bertanggung jawab\n- Memiliki sepeda motor",
                'benefits'    => "- Gaji pokok + insentif per paket\n- BBM ditanggung\n- BPJS Ketenagakerjaan\n- Motor kantor tersedia (bagi yang belum punya)",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(20),
            ],

            // PT Cikampek Hospitality
            [
                'company_index' => 4,
                'title'       => 'Resepsionis Hotel',
                'position'    => 'Resepsionis',
                'location'    => 'Cikampek',
                'job_type'    => 'full_time',
                'salary_min'  => 2900000,
                'salary_max'  => 3800000,
                'description' => "Kami mencari resepsionis hotel yang profesional dan ramah untuk melayani tamu kami.\n\nTanggung jawab:\n- Menyambut dan melayani tamu check-in/check-out\n- Menangani reservasi kamar\n- Memberikan informasi fasilitas hotel\n- Menangani keluhan tamu dengan profesional",
                'qualifications' => "- Lulusan SMK Perhotelan / Pariwisata\n- Berpenampilan menarik dan rapi\n- Komunikatif dalam Bahasa Indonesia dan Inggris dasar\n- Bersedia kerja shift\n- Berjiwa hospitality",
                'benefits'    => "- Gaji + service charge\n- Seragam kerja disediakan\n- Makan saat shift\n- BPJS lengkap\n- Pelatihan hospitaliti",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(40),
            ],
            [
                'company_index' => 4,
                'title'       => 'Pramusaji Restoran',
                'position'    => 'Pramusaji',
                'location'    => 'Cikampek',
                'job_type'    => 'part_time',
                'salary_min'  => 1800000,
                'salary_max'  => 2500000,
                'description' => "Dibutuhkan pramusaji part-time untuk restoran hotel kami.\n\nTanggung jawab:\n- Melayani pesanan makanan dan minuman tamu\n- Menjaga kebersihan meja dan area restoran\n- Membantu persiapan event dan buffet\n- Berkoordinasi dengan tim dapur",
                'qualifications' => "- Lulusan SMK Perhotelan / Tata Boga\n- Ramah dan cekatan\n- Bersedia kerja shift (termasuk malam dan weekend)\n- Berpengampilan bersih dan rapi",
                'benefits'    => "- Gaji sesuai jam kerja\n- Makan saat shift\n- Pengalaman kerja di hotel bintang tiga",
                'status'      => 'active',
                'deadline'    => Carbon::now()->addDays(12),
            ],
        ];

        $jobs = [];
        foreach ($jobsData as $d) {
            $company = $companies[$d['company_index']];
            $job = Job::firstOrCreate(
                ['company_id' => $company->id, 'title' => $d['title']],
                [
                    'position'       => $d['position'],
                    'location'       => $d['location'],
                    'job_type'       => $d['job_type'],
                    'salary_min'     => $d['salary_min'],
                    'salary_max'     => $d['salary_max'],
                    'description'    => $d['description'],
                    'qualifications' => $d['qualifications'],
                    'benefits'       => $d['benefits'],
                    'status'         => $d['status'],
                    'deadline'       => $d['deadline'],
                ]
            );
            $jobs[] = $job;
        }

        return $jobs;
    }

    // ─── SISWA ────────────────────────────────────────────────────
    private function createStudents(): array
    {
        $data = [
            [
                'email' => 'budi.santoso@siswa.bkk.com',
                'name'  => 'Budi Santoso',
                'phone' => '081234560001',
                'bio'   => 'Lulusan SMK jurusan Teknik Mesin, siap bekerja keras dan belajar hal baru.',
                'nisn'  => '0012345601',
                'major' => 'Teknik Mesin',
                'graduation_year' => 2024,
                'address' => 'Jl. Melati No. 5, Cikampek Barat',
            ],
            [
                'email' => 'siti.rahayu@siswa.bkk.com',
                'name'  => 'Siti Rahayu',
                'phone' => '081234560002',
                'bio'   => 'Lulusan SMK Akuntansi dengan keahlian Microsoft Office dan pembukuan.',
                'nisn'  => '0012345602',
                'major' => 'Akuntansi',
                'graduation_year' => 2024,
                'address' => 'Jl. Anggrek No. 12, Cikampek',
            ],
            [
                'email' => 'rizky.pratama@siswa.bkk.com',
                'name'  => 'Rizky Pratama',
                'phone' => '081234560003',
                'bio'   => 'Siswa RPL yang gemar coding dan pengembangan aplikasi web.',
                'nisn'  => '0012345603',
                'major' => 'Rekayasa Perangkat Lunak',
                'graduation_year' => 2024,
                'address' => 'Perumahan Cikampek Baru Blok C No. 8',
            ],
            [
                'email' => 'dewi.anggraini@siswa.bkk.com',
                'name'  => 'Dewi Anggraini',
                'phone' => '081234560004',
                'bio'   => 'Lulusan SMK Perhotelan yang bersemangat dan komunikatif.',
                'nisn'  => '0012345604',
                'major' => 'Perhotelan',
                'graduation_year' => 2024,
                'address' => 'Jl. Pramuka No. 33, Cikampek Timur',
            ],
            [
                'email' => 'andi.kurniawan@siswa.bkk.com',
                'name'  => 'Andi Kurniawan',
                'phone' => '081234560005',
                'bio'   => 'Lulusan TKJ dengan keahlian jaringan komputer dan troubleshooting hardware.',
                'nisn'  => '0012345605',
                'major' => 'Teknik Komputer dan Jaringan',
                'graduation_year' => 2024,
                'address' => 'Jl. Veteran No. 17, Cikampek',
            ],
            [
                'email' => 'maya.fitriani@siswa.bkk.com',
                'name'  => 'Maya Fitriani',
                'phone' => '081234560006',
                'bio'   => 'Siswa Tata Boga yang kreatif dan berpengalaman dalam catering sekolah.',
                'nisn'  => '0012345606',
                'major' => 'Tata Boga',
                'graduation_year' => 2025,
                'address' => 'Jl. Pahlawan No. 21, Cikampek',
            ],
            [
                'email' => 'dimas.setiawan@siswa.bkk.com',
                'name'  => 'Dimas Setiawan',
                'phone' => '081234560007',
                'bio'   => 'Lulusan Teknik Otomotif dengan pengalaman magang di bengkel resmi.',
                'nisn'  => '0012345607',
                'major' => 'Teknik Otomotif',
                'graduation_year' => 2024,
                'address' => 'Desa Wadas, Cikampek Barat',
            ],
            [
                'email' => 'nurul.hidayah@siswa.bkk.com',
                'name'  => 'Nurul Hidayah',
                'phone' => '081234560008',
                'bio'   => 'Siswa Administrasi Perkantoran yang terorganisir dan bertanggung jawab.',
                'nisn'  => '0012345608',
                'major' => 'Administrasi Perkantoran',
                'graduation_year' => 2025,
                'address' => 'Jl. Cikarang No. 4, Cikampek',
            ],
        ];

        $students = [];
        foreach ($data as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    'name'              => $d['name'],
                    'password'          => Hash::make('password123'),
                    'role'              => 'student',
                    'phone'             => $d['phone'],
                    'bio'               => $d['bio'],
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['student']);

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nisn'            => $d['nisn'],
                    'major'           => $d['major'],
                    'graduation_year' => $d['graduation_year'],
                    'address'         => $d['address'],
                ]
            );

            $students[] = $user;
        }

        return $students;
    }

    // ─── LAMARAN ──────────────────────────────────────────────────
    private function createApplications(array $students, array $jobs): void
    {
        // Pasangan lamaran yang realistis [index_siswa => [index_lowongan, status]]
        $pairs = [
            // Budi (Tek. Mesin) → lamaran ke operator & teknisi
            [0, 0, 'accepted',     Carbon::now()->subDays(20)],
            [0, 1, 'under_review', Carbon::now()->subDays(10)],

            // Siti (Akuntansi) → kasir & admin
            [1, 4, 'interviewed',  Carbon::now()->subDays(15)],
            [1, 5, 'submitted',    Carbon::now()->subDays(5)],

            // Rizky (RPL) → web dev & IT support
            [2, 2, 'under_review', Carbon::now()->subDays(8)],
            [2, 3, 'submitted',    Carbon::now()->subDays(3)],

            // Dewi (Perhotelan) → resepsionis & pramusaji
            [3, 6, 'accepted',     Carbon::now()->subDays(25)],
            [3, 7, 'interviewed',  Carbon::now()->subDays(12)],

            // Andi (TKJ) → IT support & web dev
            [4, 3, 'accepted',     Carbon::now()->subDays(18)],
            [4, 2, 'rejected',     Carbon::now()->subDays(22)],

            // Maya (Tata Boga) → pramusaji & kasir
            [5, 7, 'submitted',    Carbon::now()->subDays(2)],
            [5, 4, 'under_review', Carbon::now()->subDays(6)],

            // Dimas (Tek. Otomotif) → operator & kurir
            [6, 0, 'interviewed',  Carbon::now()->subDays(14)],
            [6, 6, 'submitted',    Carbon::now()->subDays(4)],  // kurir (index 6 = kurir)

            // Nurul (Adm. Perkantoran) → admin & kasir
            [7, 5, 'accepted',     Carbon::now()->subDays(30)],
            [7, 4, 'rejected',     Carbon::now()->subDays(28)],
        ];

        $coverLetters = [
            'accepted' => "Dengan hormat,\n\nSaya mengajukan lamaran untuk posisi yang ditawarkan. Saya adalah lulusan SMK dengan nilai akademis yang baik dan semangat belajar yang tinggi.\n\nSelama di sekolah, saya aktif dalam praktik kejuruan dan kegiatan ekstrakurikuler yang relevan dengan posisi ini. Saya yakin dapat memberikan kontribusi nyata bagi perusahaan Bapak/Ibu.\n\nSaya siap bekerja keras, belajar cepat, dan beradaptasi dengan lingkungan kerja profesional. Mohon pertimbangan positif dari Bapak/Ibu.\n\nHormat saya,",

            'interviewed' => "Kepada Yth. HRD,\n\nSaya tertarik melamar posisi ini karena sesuai dengan keahlian yang saya miliki dari pendidikan di SMK. Saya memiliki pengalaman praktik kerja industri yang relevan.\n\nSaya adalah pribadi yang disiplin, mau belajar, dan bisa bekerja sama dalam tim. Saya siap mengikuti proses seleksi lebih lanjut.\n\nTerima kasih atas perhatiannya.",

            'under_review' => "Dengan hormat,\n\nMelalui surat ini, saya bermaksud melamar pekerjaan di perusahaan Bapak/Ibu. Saya percaya bahwa kemampuan dan latar belakang pendidikan saya sesuai dengan kebutuhan posisi ini.\n\nSaya siap memberikan yang terbaik dan berkembang bersama perusahaan. Saya mohon kesempatan untuk dapat mengikuti proses rekrutmen.\n\nHormat saya,",

            'submitted' => "Yth. Tim Rekrutmen,\n\nSaya dengan ini melamar untuk posisi yang ditawarkan. Sebagai lulusan SMK MUTU Cikampek, saya memiliki dasar pengetahuan dan keterampilan yang relevan.\n\nSaya sangat antusias untuk berkontribusi di perusahaan ini dan berharap dapat berdiskusi lebih lanjut dalam sesi wawancara.\n\nTerima kasih.",

            'rejected' => "Dengan hormat,\n\nSaya tertarik melamar untuk posisi yang Bapak/Ibu tawarkan. Meskipun saya baru lulus, saya yakin semangat dan kemampuan saya dapat menjadi nilai tambah bagi perusahaan.\n\nSaya siap belajar dan berkembang. Besar harapan saya untuk mendapat kesempatan bergabung.\n\nHormat saya,",
        ];

        foreach ($pairs as [$si, $ji, $status, $createdAt]) {
            if (!isset($students[$si]) || !isset($jobs[$ji])) continue;

            $existing = Application::where('user_id', $students[$si]->id)
                ->where('job_id', $jobs[$ji]->id)
                ->first();

            if (!$existing) {
                Application::create([
                    'user_id'      => $students[$si]->id,
                    'job_id'       => $jobs[$ji]->id,
                    'cover_letter' => $coverLetters[$status] . "\n" . $students[$si]->name,
                    'status'       => $status,
                    'created_at'   => $createdAt,
                    'updated_at'   => $createdAt,
                ]);
            }
        }
    }

    // ─── BOOKMARK ─────────────────────────────────────────────────
    private function createBookmarks(array $students, array $jobs): void
    {
        $bookmarks = [
            [0, 2], [0, 3],
            [1, 4], [1, 5],
            [2, 2], [2, 3],
            [3, 6], [3, 7],
            [4, 2], [4, 3],
            [5, 6], [5, 7],
            [6, 0], [6, 1],
            [7, 4], [7, 5],
        ];

        foreach ($bookmarks as [$si, $ji]) {
            if (!isset($students[$si]) || !isset($jobs[$ji])) continue;
            $student = $students[$si];
            $jobId = $jobs[$ji]->id;
            if (!$student->bookmarks()->where('job_id', $jobId)->exists()) {
                $student->bookmarks()->attach($jobId);
            }
        }
    }
}
