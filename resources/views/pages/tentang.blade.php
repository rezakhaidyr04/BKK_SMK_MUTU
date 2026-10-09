<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Tentang BKKMu" subtitle="Bursa Kerja Khusus SMK TI Muhammadiyah Cikampek — menjembatani talenta muda dengan dunia kerja." eyebrow="BKKMu › Tentang">
            <x-slot:chips>
                <span class="page-banner__chip">Sejak 2026 · Cikampek</span>
                <span class="page-banner__chip">Untuk umum</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Apa itu BKKMu?</h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    <strong class="text-gray-900">BKKMu</strong> adalah platform digital <strong class="text-gray-900">Bursa Kerja Khusus (BKK) SMK TI Muhammadiyah Cikampek</strong>
                    yang menghubungkan pencari kerja — bukan hanya siswa/alumni, tapi masyarakat umum — dengan perusahaan mitra.
                    Kami membantu di tiga sisi: pencari kerja menemukan lowongan yang cocok, perusahaan menemukan talent berkualitas,
                    dan sekolah memantau penyaluran kerja lulusannya.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-2">🎯 Misi kami</h3>
                    <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Memudahkan masyarakat mencari pekerjaan</li>
                        <li>Mempermudah perusahaan merekrut talent</li>
                        <li>Meningkatkan keterserapan lulusan</li>
                    </ul>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-2">🛠️ Yang bisa Anda lakukan</h3>
                    <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Cari & lamar lowongan terverifikasi</li>
                        <li>Buat CV ramah ATS + kelola sertifikat</li>
                        <li>Ikuti job fair, seminar & berita karir</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Hubungi kami</h2>
                <p class="text-sm text-gray-600 mb-4">Ada pertanyaan, kemitraan perusahaan, atau kendala akun? Tim BKK siap membantu.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('contact.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">Hubungi via formulir</a>
                    <a href="mailto:bkksmkmutu3@gmail.com" class="inline-flex items-center px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">bkksmkmutu3@gmail.com</a>
                </div>
                <p class="text-xs text-gray-400 mt-4">SMK TI Muhammadiyah Cikampek · Cikampek, Jawa Barat · (0267) 123-456</p>
            </div>
        </div>
    </div>
</x-app-layout>
