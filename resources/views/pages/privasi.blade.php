<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Kebijakan Privasi" subtitle="Terakhir diperbarui: Oktober 2026. Ringkas, jelas, tanpa jargon berlebih." eyebrow="BKKMu › Privasi">
            <x-slot:chips>
                <span class="page-banner__chip">Perlindungan data</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">1. Data yang kami kumpulkan</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Identitas akun: nama, email, nomor HP, foto profil.</li>
                        <li>Profil karir: pendidikan, pengalaman, keahlian, portofolio, CV, sertifikat.</li>
                        <li>Dokumen lamaran: surat lamaran, CV, SKCK/ijazah pendukung (PDF, penyimpanan privat).</li>
                        <li>Data teknis: log aktivitas admin, preferensi tampilan, dan data tracing anonim.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">2. Cara data digunakan</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Menjalankan layanan inti: pencocokan lowongan, lamaran, notifikasi status.</li>
                        <li>Verifikasi perusahaan dan pencegahan penipuan lowongan.</li>
                        <li>Laporan agregat penyaluran kerja (tracer study) tanpa mengidentifikasi individu.</li>
                        <li>Kami <strong class="text-gray-900">tidak menjual</strong> data pribadi Anda.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">3. Siapa yang bisa melihat data Anda</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Perusahaan pemilik lowongan: hanya dokumen lamaran yang Anda kirimkan ke mereka. Nomor HP disamarkan dan baru bisa dihubungi via WhatsApp setelah wawancara dijadwalkan.</li>
                        <li>Admin BKK: untuk verifikasi, moderasi, dan dukungan teknis.</li>
                        <li>Publik: hanya nama tampilan dan ulasan yang Anda pilih untuk dipublikasikan.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">4. Hak Anda</h2>
                    <p>Anda dapat memperbarui profil, mengunduh/menghapus dokumen dan CV, menarik lamaran yang masih berstatus diajukan, serta meminta penghapusan akun via halaman profil atau email ke <a class="text-blue-600 underline" href="mailto:bkksmkmutu3@gmail.com">bkksmkmutu3@gmail.com</a>.</p>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">5. Keamanan & penyimpanan</h2>
                    <p>Dokumen sensitif disimpan di disk privat (bukan URL publik), akses dicek per-izin, password di-hash, dan seluruh form dilindungi CSRF. Tidak ada sistem yang 100% kebal — segera laporkan aktivitas mencurigakan via halaman <a class="text-blue-600 underline" href="{{ route('contact.index') }}">Kontak</a>.</p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
