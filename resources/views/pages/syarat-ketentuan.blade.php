<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Syarat & Ketentuan" subtitle="Aturan main yang adil untuk pencari kerja dan perusahaan." eyebrow="BKKMu › Aturan">
            <x-slot:chips>
                <span class="page-banner__chip">Berlaku sejak 2026</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">1. Akun</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Satu orang satu akun; data identitas harus benar dan terkini.</li>
                        <li>Jaga kerahasiaan password; aktivitas dari akun Anda menjadi tanggung jawab Anda.</li>
                        <li>Admin dapat menonaktifkan akun yang melanggar, memalsukan identitas, atau menyalahgunakan sistem.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">2. Pencari kerja</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Lamaran hanya untuk lowongan aktif; satu lamaran aktif per lowongan.</li>
                        <li>Dokumen yang diunggah harus milik sendiri/asli; pemalsuan berakibat pemblokiran.</li>
                        <li>Dilarang spam lamaran massal, scraping, atau mengunggah malware.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">3. Perusahaan</h2>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Lowongan wajib legal, jelas (posisi, lokasi, kompensasi), dan tanpa pungutan ke pelamar.</li>
                        <li>Perusahaan wajib terverifikasi sebelum memposting; data pelamar hanya untuk rekrutmen.</li>
                        <li>Lowongan fiktif, MLM berkedok rekrutmen, atau pungli = suspend permanen + blacklist.</li>
                    </ul>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">4. Konten & tanggung jawab platform</h2>
                    <p>BKKMu menyediakan platform perantara dan berupaya memverifikasi perusahaan, namun keputusan rekrutmen sepenuhnya milik perusahaan. Selalu verifikasi ulang tawaran yang mencurigakan dan jangan pernah membayar untuk melamar kerja.</p>
                </section>
                <section>
                    <h2 class="font-bold text-gray-900 mb-2">5. Perubahan & kontak</h2>
                    <p>Aturan ini dapat diperbarui mengikuti kebutuhan operasional; versi terbaru selalu tampil di halaman ini. Pertanyaan: <a class="text-blue-600 underline" href="{{ route('contact.index') }}">formulir Kontak</a> atau <a class="text-blue-600 underline" href="mailto:bkksmkmutu3@gmail.com">bkksmkmutu3@gmail.com</a>.</p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
