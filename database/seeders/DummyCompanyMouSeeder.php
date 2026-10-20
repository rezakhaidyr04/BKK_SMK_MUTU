<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Data dummy untuk 5 akun perusahaan.
 *
 * Setiap perusahaan dibuat lengkap dengan:
 * - Akun login (role: company, password: password123)
 * - Profil perusahaan terverifikasi (verification_status: verified)
 * - MoU: file PDF di disk private + nomor, tanggal tanda tangan, masa berlaku
 * - Lowongan kerja aktif (3 lowongan per perusahaan)
 *
 * Seeder ini idempotent: dijalankan ulang memperbarui data, bukan membuat duplikat.
 */
class DummyCompanyMouSeeder extends Seeder
{
    private const PASSWORD = 'password123';

    public function run(): void
    {
        $adminId = User::where('role', 'admin')->orderBy('id')->value('id');

        $this->command->info('Membuat 5 akun perusahaan beserta MoU...');

        $companies = [];
        foreach ($this->companyData() as $data) {
            $companies[] = $this->createCompany($data, $adminId);
        }

        $this->command->info('Membuat lowongan kerja...');
        $jobCount = 0;
        foreach ($this->jobData() as $data) {
            $company = $companies[$data['company_index']] ?? null;
            if (! $company) {
                continue;
            }
            $this->createJob($company, $data);
            $jobCount++;
        }

        $this->command->newLine();
        $this->command->info('Data dummy perusahaan + MoU + lowongan berhasil dibuat!');
        $this->command->table(
            ['Jenis Data', 'Jumlah'],
            [
                ['Perusahaan', count($companies)],
                ['Lowongan', $jobCount],
                ['Total akun perusahaan', User::where('role', 'company')->count()],
            ]
        );
        $this->command->info('Password semua akun perusahaan dummy: '.self::PASSWORD);
    }

    // ─── PERUSAHAAN ───────────────────────────────────────────────
    private function createCompany(array $d, ?int $adminId): Company
    {
        $user = User::withTrashed()->where('email', $d['email'])->first();
        if (! $user) {
            $user = new User();
            $user->email = $d['email'];
        }
        if ($user->trashed()) {
            $user->restore();
        }
        $user->forceFill([
            'name'                  => $d['name'],
            'password'              => Hash::make(self::PASSWORD),
            'role'                  => 'company',
            'is_active'             => true,
            'must_change_password' => false,
            'email_verified_at'     => now(),
        ])->save();
        $user->syncRoles(['company']);

        $company = Company::withTrashed()->where('user_id', $user->id)->first();
        if (! $company) {
            $company = new Company();
            $company->user_id = $user->id;
        }
        if ($company->trashed()) {
            $company->restore();
        }

        $company->forceFill([
            'name'                => $d['name'],
            'industry'            => $d['industry'],
            'description'         => $d['description'],
            'website'             => $d['website'],
            'email'               => $d['email'],
            'phone'               => $d['phone'],
            'address'             => $d['address'],
            'latitude'            => $d['latitude'],
            'longitude'           => $d['longitude'],
            'maps_url'            => $d['maps_url'],
            'tax_number'          => $d['tax_number'],
            'mou_number'          => $d['mou_number'],
            'mou_signed_at'       => $d['mou_signed_at'],
            'mou_expires_at'      => $d['mou_expires_at'],
            'verification_status' => 'verified',
            'is_verified'         => true,
            'reviewed_by'         => $adminId,
            'reviewed_at'         => now(),
        ])->save();

        // MoU: file PDF dummy disimpan di disk private (prefix company_mou/).
        $mouPath = $this->storeMouFile($company, $d);
        if ($mouPath) {
            $company->forceFill(['mou_path' => $mouPath])->save();
        }

        return $company;
    }

    /**
     * Buat file MoU PDF dummy (dokumen kerja sama) di disk private.
     * Idempotent: nama file deterministik per perusahaan + nomor MoU.
     */
    private function storeMouFile(Company $company, array $d): ?string
    {
        $fileName = 'company_mou/'.Str::slug($company->name).'-'.Str::slug($d['mou_number']).'.pdf';

        if (Storage::disk('private')->exists($fileName)) {
            return $fileName;
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dummy.mou', [
                'company'    => $company,
                'mouNumber'  => $d['mou_number'],
                'signedAt'   => $d['mou_signed_at'],
                'expiresAt'  => $d['mou_expires_at'],
                'schoolName' => 'SMK TI Muhammadiyah Cikampek',
                'issuedAt'   => now()->format('d F Y'),
            ])->setPaper('a4', 'portrait');

            Storage::disk('private')->put($fileName, $pdf->output());

            return $fileName;
        } catch (\Throwable $e) {
            $this->command->warn('Gagal membuat file MoU PDF untuk '.$company->name.': '.$e->getMessage());

            return null;
        }
    }

    // ─── LOWONGAN ─────────────────────────────────────────────────
    private function createJob(Company $company, array $d): Job
    {
        $job = Job::withTrashed()->where('company_id', $company->id)
            ->where('title', $d['title'])
            ->first();

        if (! $job) {
            $job = new Job();
            $job->company_id = $company->id;
            $job->title = $d['title'];
        }
        if ($job->trashed()) {
            $job->restore();
        }

        $job->forceFill([
            'company_name'   => $company->name,
            'position'       => $d['position'],
            'location'       => $d['city'].', '.$d['province'],
            'province'       => $d['province'],
            'city'           => $d['city'],
            'district'       => $d['district'] ?? null,
            'job_type'       => $d['job_type'],
            'salary_min'     => $d['salary_min'],
            'salary_max'     => $d['salary_max'],
            'description'    => $d['description'],
            'qualifications' => $d['qualifications'],
            'education'      => $d['education'] ?? null,
            'experience'     => $d['experience'] ?? null,
            'gender'         => $d['gender'] ?? null,
            'age_range'      => $d['age_range'] ?? null,
            'work_hours'     => $d['work_hours'] ?? null,
            'benefits'       => $d['benefits'] ?? null,
            'deadline'       => $d['deadline'],
            'status'         => 'active',
        ])->save();

        return $job;
    }

    // ─── DATA PERUSAHAAN ─────────────────────────────────────────
    private function companyData(): array
    {
        $signed = Carbon::now()->subMonths(4)->startOfMonth();

        return [
            [
                'email'          => 'demo.maju@perusahaan.co.id',
                'name'           => 'PT Karawang Manufaktur Nusantara',
                'industry'       => 'Manufaktur & Otomotif',
                'description'    => 'Pabrikan komponen otomotif dan alat berat kelas dunia yang beroperasi di kawasan industri Karawang, Jawa Barat.',
                'website'        => 'https://karawangmanufaktur.co.id',
                'phone'          => '0267 555101',
                'address'        => 'Jl. Raya Industri KM 3, Karawang Barat, Kabupaten Karawang, Jawa Barat 41361',
                'latitude'       => -6.476244,
                'longitude'      => 107.553896,
                'maps_url'       => 'https://maps.google.com/?q=Kawasan+Industri+Karawang',
                'tax_number'     => '01.234.567.8-012.000',
                'mou_number'     => 'MOU/BKK-SMK-MUTU/2026/001',
                'mou_signed_at'  => $signed->copy()->toDateString(),
                'mou_expires_at' => $signed->copy()->addYear()->toDateString(),
            ],
            [
                'email'          => 'demo.digital@perusahaan.co.id',
                'name'           => 'PT Digital Solution Teknologi',
                'industry'       => 'Teknologi Informasi',
                'description'    => 'Perusahaan pengembangan perangkat lunak, sistem informasi, dan solusi digital untuk industri manufaktur serta UMKM di Jabodetabek.',
                'website'        => 'https://digitalsolution.co.id',
                'phone'          => '021 8899 4321',
                'address'        => 'Jl. Raya Cikarang Barat No. 25, Cikarang Barat, Kabupaten Bekasi, Jawa Barat 17530',
                'latitude'       => -6.271893,
                'longitude'      => 107.132674,
                'maps_url'       => 'https://maps.google.com/?q=Cikarang+Barat',
                'tax_number'     => '09.876.543.2-003.000',
                'mou_number'     => 'MOU/BKK-SMK-MUTU/2026/002',
                'mou_signed_at'  => $signed->copy()->subMonth()->toDateString(),
                'mou_expires_at' => $signed->copy()->addYear()->subMonth()->toDateString(),
            ],
            [
                'email'          => 'demo.sentosa@perusahaan.co.id',
                'name'           => 'PT Prima Sentosa Food Industry',
                'industry'       => 'Makanan & Minuman',
                'description'    => 'Produsen makanan dan minuman dengan merek nasional, menerapkan standar Food Safety, dan tersebar di Karawang Timur serta Cikarang.',
                'website'        => 'https://primasentosafood.co.id',
                'phone'          => '0267 442200',
                'address'        => 'Jl. Raya Karawang-Tegal No. 88, Karawang Timur, Kabupaten Karawang, Jawa Barat 41311',
                'latitude'       => -6.321992,
                'longitude'      => 107.532714,
                'maps_url'       => 'https://maps.google.com/?q=Karawang+Timur',
                'tax_number'     => '02.998.776.5-012.000',
                'mou_number'     => 'MOU/BKK-SMK-MUTU/2026/003',
                'mou_signed_at'  => $signed->copy()->subMonths(2)->toDateString(),
                'mou_expires_at' => $signed->copy()->addYear()->subMonths(2)->toDateString(),
            ],
            [
                'email'          => 'demo.sinarbaja@perusahaan.co.id',
                'name'           => 'PT Sinar Baja Nusantara',
                'industry'       => 'Logam & Konstruksi',
                'description'    => 'Produsen baja, profil, dan konstruksi baja ringan untuk bangunan industri dan infrastruktur di Jababeka.',
                'website'        => 'https://sinarbaja.co.id',
                'phone'          => '021 8998 1200',
                'address'        => 'Jl. Raya Jababeka No. 17, Cikarang Timur, Kabupaten Bekasi, Jawa Barat 17530',
                'latitude'       => -6.320991,
                'longitude'      => 107.147236,
                'maps_url'       => 'https://maps.google.com/?q=Jababeka+Cikarang',
                'tax_number'     => '03.112.445.9-412.000',
                'mou_number'     => 'MOU/BKK-SMK-MUTU/2026/004',
                'mou_signed_at'  => $signed->copy()->subMonths(3)->toDateString(),
                'mou_expires_at' => $signed->copy()->addYear()->subMonths(3)->toDateString(),
            ],
            [
                'email'          => 'demo.garmen@perusahaan.co.id',
                'name'           => 'PT Nusantara Garmen Sentosa',
                'industry'       => 'Tekstil & Garmen',
                'description'    => 'Pabrik garmen berorientasi ekspor dengan fasilitas produksi modern di Subang dan tenaga kerja terlatih.',
                'website'        => 'https://nusantaragarmen.co.id',
                'phone'          => '0267 415777',
                'address'        => 'Jl. Raya Subang KM 5, Subang, Kabupaten Subang, Jawa Barat 41211',
                'latitude'       => -6.569311,
                'longitude'      => 107.762543,
                'maps_url'       => 'https://maps.google.com/?q=Subang+Jawa+Barat',
                'tax_number'     => '04.556.889.1-412.000',
                'mou_number'     => 'MOU/BKK-SMK-MUTU/2026/005',
                'mou_signed_at'  => $signed->copy()->subMonths(5)->toDateString(),
                'mou_expires_at' => $signed->copy()->addYear()->subMonths(5)->toDateString(),
            ],
        ];
    }

    // ─── DATA LOWONGAN ───────────────────────────────────────────
    private function jobData(): array
    {
        $now = Carbon::now();

        return [
            // ── PT Karawang Manufaktur Nusantara ───────────────────
            [
                'company_index'  => 0,
                'title'          => 'Operator Produksi (Mesin Pres)',
                'position'       => 'Operator Produksi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Barat',
                'job_type'       => 'full_time',
                'salary_min'     => 3200000,
                'salary_max'     => 4200000,
                'description'    => "Bergabunglah dengan lini produksi stamping dan welding kami di kawasan industri Karawang.\n\nTanggung jawab:\n- Mengoperasikan mesin press dan stamping sesuai SOP\n- Melakukan pemeriksaan visual hasil produksi setiap shift\n- Melaporkan abnormality mesin kepada supervisor produksi\n- Menjaga kebersihan dan kerapian area kerja\n- Mematuhi aturan K3 (APD, safety shoes, safety helmet)",
                'qualifications' => "Lulusan SMK Teknik Pemesinan, Teknik Mesin, atau Teknik Otomotif\nMemahami dasar-dasar proses metal forming\nMampu bekerja secara shift (3 shift)\nSehat jasmani dan rohani\nBersedia mengikuti safety induction wajib\nTeliti, disiplin, dan siap bekerja keras",
                'education'      => 'SMK / SMA sederajat',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki',
                'age_range'      => 'Maksimal 25 tahun',
                'work_hours'     => 'Shift 3 (pagi, siang, malam), 6 hari kerja',
                'benefits'       => "Gaji pokok + uang makan + uang shift\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan hari raya (THR)\nSeragam dan alat pelindung diri disediakan\nBonus produktivitas bulanan\nPelatihan dan sertifikasi keterampilan",
                'deadline'       => $now->copy()->addDays(30)->toDateString(),
            ],
            [
                'company_index'  => 0,
                'title'          => 'Teknisi Mesin dan Pemeliharaan',
                'position'       => 'Teknisi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Barat',
                'job_type'       => 'full_time',
                'salary_min'     => 4000000,
                'salary_max'     => 5500000,
                'description'    => "Dibutuhkan teknisi mesin yang terbiasa menangani mesin industri stamping dan forming.\n\nTanggung jawab:\n- Melakukan perawatan preventif dan korektif mesin produksi\n- Troubleshooting pada masalah mesin hidrolik dan pneumatik\n- Membuat laporan harian kondisi mesin\n- Menyusun sparepart kebutuhan bagian maintenance\n- Mendukung proses improvement di lini produksi",
                'qualifications' => "Lulusan SMK Teknik Mesin atau SMK Teknik Pemesinan\nMemahami gambar teknik mesin\nMenguasai dasar pneumatik, hidrolik, dan electrical troubleshooting\nPengalaman minimal 1 tahun, fresh graduate dipersilakan\nRajin, teliti, dan siap bekerja lembur",
                'education'      => 'SMK Teknik Mesin / D3 Teknik Mesin',
                'experience'     => 'Minimal 1 tahun',
                'gender'         => 'Laki-laki',
                'age_range'      => 'Maksimal 30 tahun',
                'work_hours'     => 'Senin-Sabtu, 08.00-17.00',
                'benefits'       => "Gaji kompetitif + tunjangan lembur\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan momen bahaya (shift malam)\nPelatihan mesin dan sertifikasi nasional\nJenjang karier ke Supervisor Maintenance",
                'deadline'       => $now->copy()->addDays(25)->toDateString(),
            ],
            [
                'company_index'  => 0,
                'title'          => 'Quality Control Komponen',
                'position'       => 'Quality Control',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 3300000,
                'salary_max'     => 4300000,
                'description'    => "Menjaga kualitas produk komponen otomotif sebelum dikirim ke pelanggan.\n\nTanggung jawab:\n- Melakukan inspeksi dimensi produk dengan alat ukur (caliper, micrometer)\n- Membuat laporan hasil inspeksi per batch produksi\n- Memberikan feedback kepada tim produksi\n- Menjaga dokumen kontrol mutu\n- Berkoordinasi dengan tim customer quality",
                'qualifications' => "Lulusan SMK Teknik Pemesinan atau Teknik Industri\nTeliti dan jeli melihat detail\nMemahami dasar pengukuran metrologi\nDisiplin dan dapat bekerja tenggat waktu\nKomunikatif dan siap bekerja dalam tim",
                'education'      => 'SMK Teknik Pemesinan / Teknik Industri',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 27 tahun',
                'work_hours'     => 'Senin-Sabtu, 07.00-16.00',
                'benefits'       => "Gaji + tunjangan makan\nBPJS Kesehatan dan Ketenagakerjaan\nPelatihan QC dan audit internal\nSeragam kerja dan alat pelindung diri",
                'deadline'       => $now->copy()->addDays(28)->toDateString(),
            ],

            // ── PT Digital Solution Teknologi ──────────────────────
            [
                'company_index'  => 1,
                'title'          => 'Junior Web Developer',
                'position'       => 'Developer',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Barat',
                'job_type'       => 'full_time',
                'salary_min'     => 3500000,
                'salary_max'     => 5000000,
                'description'    => "Bangun produk digital yang berdampak bersama tim engineer kami.\n\nTanggung jawab:\n- Mengembangkan fitur front-end dan back-end sesuai requirement\n- Menulis kode yang bersih, terdokumentasi, dan mudah diuji\n- Ikut code review dan daily stand-up\n- Membuat dokumentasi teknis\n- Membantu troubleshooting bug yang dilaporkan pengguna",
                'qualifications' => "Lulusan SMK Rekayasa Perangkat Lunak / TKJ / D3 Informatika\nMenguasai HTML, CSS, dan JavaScript\nNilai tambah: dasar-dasar PHP/Laravel atau React\nFamiliar dengan MySQL dan Git\nBersedia bekerja dalam tim dan cepat belajar",
                'education'      => 'SMK Rekayasa Perangkat Lunak / D3 Informatika',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 25 tahun',
                'work_hours'     => 'Senin-Jumat, 09.00-18.00 (hybrid 3 hari di kantor)',
                'benefits'       => "Gaji kompetitif + tunjangan internet dan transport\nLaptop dan perangkat kerja disediakan\nBPJS Kesehatan dan Ketenagakerjaan\nJam kerja fleksibel\nPelatihan dan sertifikasi teknologi\nLingkungan kerja modern dan kolaboratif",
                'deadline'       => $now->copy()->addDays(35)->toDateString(),
            ],
            [
                'company_index'  => 1,
                'title'          => 'Staf IT Support dan Jaringan',
                'position'       => 'IT Support',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Barat',
                'job_type'       => 'full_time',
                'salary_min'     => 3000000,
                'salary_max'     => 4000000,
                'description'    => "Mendukung operasional TI kantor dan klien kami.\n\nTanggung jawab:\n- Instalasi dan konfigurasi hardware, software, dan printer\n- Menangani tiket helpdesk pengguna\n- Maintenance jaringan kantor (LAN, Wi-Fi, router, switch)\n- Membuat dokumentasi aset dan SOP TI\n- Membantu setup perangkat baru karyawan",
                'qualifications' => "Lulusan SMK Teknik Komputer dan Jaringan / TKJ\nMemahami jaringan dasar (IP addressing, LAN, Wi-Fi)\nMampu troubleshooting Windows, Linux dasar, dan Microsoft Office\nSabar, komunikatif, dan siap bekerja shift on-call\nSurat Izin Mengに臨 SIM B lebih diutamakan",
                'education'      => 'SMK TKJ / D3 Teknik Komputer',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 27 tahun',
                'work_hours'     => 'Senin-Jumat, 09.00-18.00',
                'benefits'       => "Gaji pokok + tunjangan transport\nBPJS Kesehatan dan Ketenagakerjaan\nSertifikasi IT ditanggung perusahaan\nTunjangan handset dan internet\nPelatihan berkala",
                'deadline'       => $now->copy()->addDays(22)->toDateString(),
            ],
            [
                'company_index'  => 1,
                'title'          => 'Magang IT (Internship) 6 Bulan',
                'position'       => 'IT Intern',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Barat',
                'job_type'       => 'internship',
                'salary_min'     => 1500000,
                'salary_max'     => 2000000,
                'description'    => "Program magang 6 bulan untuk siswa SMK agar dapat memperoleh pengalaman nyata di industri teknologi.\n\nTanggung jawab:\n- Membantu develop dan pengujian fitur aplikasi\n- Mendukung penyediaan perangkat dan jaringan\n- Membuat dokumentasi teknis sederhana\n- Dibawah bimbingan mentor bersama tim senior\n- Menyusun laporan magang",
                'qualifications' => "Siswa aktif kelas XII SMK TKJ / Rekayasa Perangkat Lunak\nNilai rata-rata minimal 80\nFamiliar dengan dasar pemrograman dan jaringan\nSemangat belajar tinggi dan disiplin\nBerkomunikasi dengan baik",
                'education'      => 'SMK kelas XI-XII',
                'experience'     => 'Tidak wajib (program magang)',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => '17-20 tahun',
                'work_hours'     => 'Senin-Jumat, 09.00-15.00',
                'benefits'       => "Uang saku bulanan\nSertifikat magang teruji\nMentoring dari tim senior\nPelatihan hard skill dan soft skill\nKonversi ke karyawan tetap untuk terpilih",
                'deadline'       => $now->copy()->addDays(20)->toDateString(),
            ],

            // ── PT Prima Sentosa Food Industry ─────────────────────
            [
                'company_index'  => 2,
                'title'          => 'Operator Produksi Food and Beverage',
                'position'       => 'Operator Produksi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 2900000,
                'salary_max'     => 3800000,
                'description'    => "Menjalankan lini produksi makanan dan minuman dengan standar higienis yang tinggi.\n\nTanggung jawab:\n- Mengoperasikan mesin processing dan filling sesuai SOP\n- Memantau parameter suhu dan waktu proses\n- Melakukan sanitasi alat dan area produksi\n- Mengisi laporan hasil produksi harian\n- Mematuhi aturan higienis dan GMP",
                'qualifications' => "Lulusan SMK Teknik Mesin, Teknik Pendingin, atau Food Processing\nSehat jasmani dan bebas dari penyakit yang mengganggu pekerjaan\nMau bekerja secara shift\nTeliti mengikuti prosedur dan standar GMP\nBersedia melakukan sanitasi area produksi",
                'education'      => 'SMK Food Processing / Teknik Pendingin',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 27 tahun',
                'work_hours'     => 'Shift 3, 6 hari kerja',
                'benefits'       => "Gaji + uang makan + uang shift\nBPJS Kesehatan dan Ketenagakerjaan\nSeragam, APD, dan hygiene kit\nTunjangan hari raya\nMakan saat shift gratis",
                'deadline'       => $now->copy()->addDays(26)->toDateString(),
            ],
            [
                'company_index'  => 2,
                'title'          => 'Staf Quality Assurance',
                'position'       => 'Quality Assurance',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 3200000,
                'salary_max'     => 4200000,
                'description'    => "Memastikan produk memenuhi standar mutu serta menerapkan HACCP.\n\nTanggung jawab:\n- Melakukan pemeriksaan mutu bahan baku dan produk jadi\n- Menerbitkan sertifikat analisis internal\n- Mencatat hasil laboratorium dan memonitor parameter\n- Mendukung audit internal dan eksternal\n- Menyarankan perbaikan proses produksi",
                'qualifications' => "Lulusan SMK Food Processing / Kimia Industri / Analis Kimia\nTeliti dan mampu membaca data\nMemahami konsep GMP dan HACCP\nDisiplin dan terdokumentasi dengan baik\nBerkomunikasi baik dengan tim produksi",
                'education'      => 'SMK Food Processing / Kimia Industri',
                'experience'     => 'Minimal 1 tahun (fresh graduate dipersilakan)',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 30 tahun',
                'work_hours'     => 'Senin-Jumat, 08.00-16.00',
                'benefits'       => "Gaji + tunjangan makan\nBPJS Kesehatan dan Ketenagakerjaan\nPelatihan HACCP dan audit\nSeragam kerja dan APD lengkap\nBonus kinerja triwulan",
                'deadline'       => $now->copy()->addDays(24)->toDateString(),
            ],
            [
                'company_index'  => 2,
                'title'          => 'Staf Gudang dan Logistik',
                'position'       => 'Staf Gudang',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Karawang',
                'district'       => 'Karawang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 2800000,
                'salary_max'     => 3600000,
                'description'    => "Mengelola stok bahan baku dan produk jadi di gudang.\n\nTanggung jawab:\n- Mencatat barang masuk dan keluar dari gudang\n- Melakukan stock opname berkala\n- Menjaga kebersihan, urutan, dan keamanan gudang\n- Membuat laporan inventaris harian\n- Berkoordinasi dengan bagian produksi dan logistik",
                'qualifications' => "Lulusan SMK Akuntansi, Administrasi Perkantoran, atau Logistik\nTeliti, rapi, dan terorganisir\nMenguasai Microsoft Excel\nMampu mengoperasikan forklift (diutamakan)\nBersedia bekerja dengan sistem shift",
                'education'      => 'SMK Akuntansi / Administrasi Perkantoran',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 30 tahun',
                'work_hours'     => 'Senin-Sabtu, 07.00-15.00',
                'benefits'       => "Gaji + tunjangan makan\nBPJS Kesehatan dan Ketenagakerjaan\nSeragam dan safety shoes\nTunjangan shift\nBonus akurasi stock opname",
                'deadline'       => $now->copy()->addDays(18)->toDateString(),
            ],

            // ── PT Sinar Baja Nusantara ───────────────────────────
            [
                'company_index'  => 3,
                'title'          => 'Operator Produksi Baja',
                'position'       => 'Operator Produksi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 3300000,
                'salary_max'     => 4400000,
                'description'    => "Beroperasi pada lini produksi baja rolled section di pabrik Jababeka.\n\nTanggung jawab:\n- Mengoperasikan mesin rolling mill sesuai parameter setting\n- Mengawasi ketebalan dan dimensi produk\n- Melakukan pengecekan alat ukur dan memberi umpan balik\n- Menjaga area kerja tetap bersih dan aman\n- Mematuhi prosedur K3 dan lockout-tagout",
                'qualifications' => "Lulusan SMK Teknik Mesin, Teknik Pemesinan, atau Teknik Industri\nMemahami operasi mesin industri\nMampu bekerja secara shift\nSehat jasmani dan rohani\nDisiplin, teliti, dan selalu mengikuti prosedur\nBersedia mengikuti safety training",
                'education'      => 'SMK Teknik Mesin / Teknik Pemesinan',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki',
                'age_range'      => 'Maksimal 28 tahun',
                'work_hours'     => 'Shift 3, 6 hari kerja',
                'benefits'       => "Gaji + uang makan + uang shift\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan hari raya\nAPD lengkap dan ruang shift yang nyaman\nPelatihan mesin dan sertifikasi",
                'deadline'       => $now->copy()->addDays(27)->toDateString(),
            ],
            [
                'company_index'  => 3,
                'title'          => 'Teknisi Las (Welder)',
                'position'       => 'Teknisi Las',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 3600000,
                'salary_max'     => 4800000,
                'description'    => "Melakukan pengelasan struktur baja pada fabrikasi dan perbaikan produk.\n\nTanggung jawab:\n- Melakukan pengelasan SMAW, GMAW, atau FCAW sesuai gambar kerja\n- Memastikan hasil las memenuhi standar mutu\n- Melakukan persiapan material dan alat las\n- Membantu dismantle dan perakitan struktur di lapangan\n- Melaporkan cacat las kepada inspector",
                'qualifications' => "Lulusan SMK Teknik Mesin / Teknik Pemesinan / Teknik Las\nMemiliki sertifikat las yang masih berlaku (diutamakan)\nMemahami gambar teknik dan prosedur las\nMampu bekerja di ketinggian dan ruang terbatas\nTeliti dan mengutamakan keselamatan kerja",
                'education'      => 'SMK Teknik Mesin / Teknik Las',
                'experience'     => 'Minimal 1 tahun',
                'gender'         => 'Laki-laki',
                'age_range'      => 'Maksimal 35 tahun',
                'work_hours'     => 'Senin-Sabtu, 07.00-16.00',
                'benefits'       => "Gaji + tunjangan lembur\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan area bahaya\nAlat kerja dan APD lengkap\nSertifikasi las ditanggung perusahaan\nBonus proyek",
                'deadline'       => $now->copy()->addDays(21)->toDateString(),
            ],
            [
                'company_index'  => 3,
                'title'          => 'Staf Administrasi Gudang',
                'position'       => 'Administrasi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Bekasi',
                'district'       => 'Cikarang Timur',
                'job_type'       => 'full_time',
                'salary_min'     => 2700000,
                'salary_max'     => 3400000,
                'description'    => "Mendukung administrasi gudang dan dokumen pengiriman barang.\n\nTanggung jawab:\n- Membuat surat jalan dan dokumen ekspor\n- Menginput data barang masuk dan keluar ke sistem\n- Menyusun laporan stok dan rekonsiliasi\n- Mengurus dokumen legalitas pengiriman\n- Menjawab permintaan dari bagian penjualan",
                'qualifications' => "Lulusan SMK Administrasi Perkantoran / Akuntansi\nMenguasai Microsoft Excel dan Word\nTeliti, rapi, dan mampu bekerja dengan target\nBerkomunikasi baik secara lisan dan tulisan\nBersedia bekerja dengan sistem shift",
                'education'      => 'SMK Administrasi Perkantoran / Akuntansi',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 27 tahun',
                'work_hours'     => 'Senin-Jumat, 08.00-17.00',
                'benefits'       => "Gaji pokok + tunjangan transport\nBPJS Kesehatan dan Ketenagakerjaan\nJam kerja teratur\nSeragam kerja\nBonus tahunan",
                'deadline'       => $now->copy()->addDays(30)->toDateString(),
            ],

            // ── PT Nusantara Garmen Sentosa ─────────────────────────
            [
                'company_index'  => 4,
                'title'          => 'Operator Mesin Jahit',
                'position'       => 'Operator Produksi',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Subang',
                'district'       => 'Subang',
                'job_type'       => 'full_time',
                'salary_min'     => 2500000,
                'salary_max'     => 3400000,
                'description'    => "Menjalankan lini produksi garment ekspor dengan target harian yang kompetitif.\n\nTanggung jawab:\n- Mengoperasikan mesin jahit sesuai standar produk\n- Menjahit sesuai pola, ukuran, dan standar kualitas\n- Memeriksa hasil jahitan sebelum masuk ke tahap QC\n- Melaporkan penyimpangan dan kerusakan kain kepada supervisor\n- Menjaga kebersihan mesin dan area produksi",
                'qualifications' => "Lulusan SMK Tata Busana / Garmen / Needle\nTeliti, cekatan, dan mampu bekerja dengan ritme cepat\nBersedia bekerja shift\nMau belajar dan konsisten mengikuti standar kerja\nMampu bekerja dalam tim",
                'education'      => 'SMK Tata Busana / Garmen',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 25 tahun',
                'work_hours'     => 'Shift 2 (pagi dan siang), 6 hari kerja',
                'benefits'       => "Gaji + uang makan + uang shift\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan hari raya\nSeragam dan aksesoris kerja\nBonus produksi\nJaminan hari kerja",
                'deadline'       => $now->copy()->addDays(23)->toDateString(),
            ],
            [
                'company_index'  => 4,
                'title'          => 'Quality Control Garmen',
                'position'       => 'Quality Control',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Subang',
                'district'       => 'Subang',
                'job_type'       => 'full_time',
                'salary_min'     => 2900000,
                'salary_max'     => 3800000,
                'description'    => "Memastikan produk garment memenuhi standar buyer sebelum dikirim.\n\nTanggung jawab:\n- Memeriksa kualitas produk secara visual dan pengukuran\n- Melakukan random inspection pada setiap lot produksi\n- Membuat laporan QC harian dan rekap mingguan\n- Memberikan feedback perbaikan ke tim produksi\n- Menjaga dokumen standar buyer",
                'qualifications' => "Lulusan SMK Teknik Tekstil / Garmen / Administrasi\nTeliti dan jeli melihat detail\nMemahami standar inspection garment\nDisiplin dan dapat bekerja dengan target\nBerkomunikasi baik dengan tim produksi",
                'education'      => 'SMK Teknik Tekstil / Garmen',
                'experience'     => 'Fresh graduate dipersilakan',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 27 tahun',
                'work_hours'     => 'Senin-Sabtu, 07.00-15.00',
                'benefits'       => "Gaji + tunjangan makan\nBPJS Kesehatan dan Ketenagakerjaan\nSeragam kerja\nPelatihan QC dan standar buyer\nBonus bulanan",
                'deadline'       => $now->copy()->addDays(20)->toDateString(),
            ],
            [
                'company_index'  => 4,
                'title'          => 'Supervisor Produksi Garmen',
                'position'       => 'Supervisor',
                'province'       => 'Jawa Barat',
                'city'           => 'Kabupaten Subang',
                'district'       => 'Subang',
                'job_type'       => 'full_time',
                'salary_min'     => 4500000,
                'salary_max'     => 6000000,
                'description'    => "Memimpin jalannya produksi garment agar target tercapai dengan kualitas terjaga.\n\nTanggung jawab:\n- Mengawasi operasional harian produksi\n- Menyusun rencana produksi harian dan penempatan tenaga kerja\n- Memastikan target output, efisiensi, dan quality terpenuhi\n- Melakukan prepare dan pelaporan produksi\n- Melaporkan progres produksi ke manajemen",
                'qualifications' => "Lulusan SMK/D3 Teknik Tekstil atau Manajemen Industri\nPengalaman minimal 2 tahun di lini garment\nLeadership baik dan mampu memimpin tim\nMenguasai perhitungan produktivitas dan penempatan kerja\nDisiplin dan orientasi hasil",
                'education'      => 'SMK Tekstil / D3 Manajemen Industri',
                'experience'     => 'Minimal 2 tahun',
                'gender'         => 'Laki-laki / Perempuan',
                'age_range'      => 'Maksimal 35 tahun',
                'work_hours'     => 'Senin-Sabtu, 07.00-16.00',
                'benefits'       => "Gaji + tunjangan jabatan\nBPJS Kesehatan dan Ketenagakerjaan\nTunjangan transport\nSeragam kerja\nBonus kinerja tahunan",
                'deadline'       => $now->copy()->addDays(30)->toDateString(),
            ],
        ];
    }
}
