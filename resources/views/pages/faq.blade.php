<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Pertanyaan Umum" subtitle="Jawaban cepat seputar akun, lamaran, CV, dan perusahaan." eyebrow="BKKMu › FAQ">
            <x-slot:chips>
                <span class="page-banner__chip">Bantuan mandiri</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section max-w-3xl mx-auto space-y-4" x-data="{ open: 0 }">
            @php
                $faqs = [
                    ['q' => 'Apakah BKKMu gratis?', 'a' => 'Ya. Mendaftar, mencari lowongan, melamar, membuat CV, dan mengikuti sebagian besar acara tidak dipungut biaya.'],
                    ['q' => 'Siapa saja yang bisa mendaftar?', 'a' => 'Masyarakat umum. Anda tidak harus siswa/alumni SMK MUTU. Pilih peran pencari kerja saat registrasi; perusahaan mendaftar lewat jalur verifikasi perusahaan.'],
                    ['q' => 'Bagaimana cara melamar lowongan?', 'a' => 'Buka halaman lowongan, pastikan email sudah terverifikasi, unggah surat lamaran (PDF wajib) lalu klik Lamar. Status bisa dipantau di menu Lamaran Saya.'],
                    ['q' => 'Mengapa tombol Lamar tidak aktif?', 'a' => 'Biasanya karena email belum diverifikasi, lowongan sudah kedaluwarsa/ditutup, atau Anda sudah pernah melamar posisi tersebut.'],
                    ['q' => 'Bagaimana perusahaan memposting lowongan?', 'a' => 'Perusahaan mendaftar, melengkapi profil + dokumen legal, menunggu verifikasi admin, lalu bisa memposting dan mengelola pelamar dari dashboard perusahaan.'],
                    ['q' => 'Apakah data saya aman?', 'a' => 'Dokumen lamaran disimpan di penyimpanan privat dan hanya bisa diakses pemilik lowongan serta Anda sendiri. Detail lengkap ada di halaman Kebijakan Privasi.'],
                    ['q' => 'Saya menemukan lowongan mencurigakan. Apa yang harus dilakukan?', 'a' => 'Jangan transfer uang atau berikan data sensitif. Laporkan via formulir Kontak dengan menyertakan tautan lowongan agar admin menindaklanjuti.'],
                    ['q' => 'Bagaimana menghubungi tim BKK?', 'a' => 'Via halaman Kontak atau email bkksmkmutu3@gmail.com. Sertakan nama akun dan tangkapan layar agar lebih cepat ditangani.'],
                ];
            @endphp
            @foreach($faqs as $i => $faq)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <button type="button" @click="open === {{ $i }} ? open = -1 : open = {{ $i }}" class="w-full flex items-center justify-between gap-3 px-5 sm:px-6 py-4 text-left">
                    <span class="font-semibold text-gray-900 text-sm sm:text-base">{{ $faq['q'] }}</span>
                    <svg x-show="open !== {{ $i }}" class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <svg x-show="open === {{ $i }}" x-cloak class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 sm:px-6 pb-5 text-sm text-gray-600 leading-relaxed">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach

            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 text-sm text-blue-800">
                Tidak menemukan jawaban? <a href="{{ route('contact.index') }}" class="font-semibold underline hover:text-blue-900">Hubungi kami</a> — tim BKK akan membantu.
            </div>
        </div>
    </div>
</x-app-layout>
