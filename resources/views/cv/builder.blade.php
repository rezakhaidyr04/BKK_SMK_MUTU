<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="CV Profesional"
            subtitle="Susun CV yang rapi, konsisten, dan siap dikirim ke perekrut."
            eyebrow="ATS Ready · PDF Export · 1 Template"
        >
            <x-slot:actions>
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <div class="text-sm font-bold text-white">1</div>
                        <div class="text-[11px] text-blue-200">Template</div>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-sm font-bold text-white">ATS</div>
                        <div class="text-[11px] text-blue-200">Siap rekruter</div>
                    </div>
                    <div class="w-px h-8 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-sm font-bold text-white">PDF</div>
                        <div class="text-[11px] text-blue-200">Siap unduh</div>
                    </div>
                </div>
            </x-slot:actions>
        </x-ui.page-banner>

        <div x-data="{
            headline: @js($previewData['headline']),
            summary: @js($previewData['summary']),
            experience: @js($previewData['experience']),
            achievement: @js($previewData['achievement'])
        }" class="page-container page-section">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 xl:col-span-9 space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-blue-50">
                            <h2 class="text-xl font-bold text-gray-900">Template CV Standar</h2>
                            <p class="text-sm text-gray-600 mt-1">Gunakan satu template CV yang rapi, konsisten, dan siap untuk perekrut.</p>
                        </div>

                        <form action="{{ route('cv.generate') }}" method="POST" class="p-6 sm:p-8 pb-28 sm:pb-8 space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_photo" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    Foto profil
                                </label>
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_skills" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    Tampilkan skill
                                </label>
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_certificates" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    Tampilkan sertifikat
                                </label>
                            </div>
                            <input type="hidden" name="template" value="modern">

                            <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shrink-0">CV</div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-900">Template standar aktif</p>
                                        <p class="text-sm text-gray-600 mt-1">Template ini dipakai otomatis untuk semua CV agar hasilnya konsisten, bersih, dan mudah dibaca perekrut.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-sm text-amber-900">
                                Tip: hasil terbaik muncul kalau profil kamu sudah lengkap. Isi nama, bio, pengalaman, skill, dan sertifikat terlebih dahulu.
                            </div>

                            <div class="grid grid-cols-1 gap-3.5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Headline CV</label>
                                    <input type="text" name="custom_headline" maxlength="120" x-model="headline" placeholder="Contoh: Admin Office, Operator Produksi, Junior Web Developer" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Posisi yang dituju</label>
                                    <input type="text" name="target_position" maxlength="120" value="{{ old('target_position', $previewData['target_position'] ?? '') }}" placeholder="Contoh: Staff Administrasi, Operator Produksi, Junior Web Developer" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Kata kunci ATS</label>
                                    <input type="text" name="ats_keywords" maxlength="300" value="{{ old('ats_keywords', $previewData['ats_keywords'] ?? '') }}" placeholder="Contoh: administrasi, microsoft excel, komunikasi, kantor" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Ringkasan singkat</label>
                                    <textarea name="custom_summary" rows="3" maxlength="1200" x-model="summary" placeholder="Tulis 2-4 kalimat yang menjelaskan siapa kamu, keahlian utama, dan target kerja." class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Pengalaman / proyek / organisasi paling relevan</label>
                                    <textarea name="custom_experience" rows="4" maxlength="2000" placeholder="Contoh:\n- Magang di toko retail selama 3 bulan\n- Membantu administrasi OSIS\n- Membuat website sekolah sederhana" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('custom_experience', $previewData['experience'] ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Pencapaian utama</label>
                                    <textarea name="custom_achievement" rows="2" maxlength="500" x-model="achievement" placeholder="Contoh: Juara 2 lomba desain poster, lulus PKL dengan predikat baik, memimpin proyek kelas." class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('custom_achievement', $previewData['achievement'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="hidden sm:inline-flex w-full items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                 </svg>
                                 Buat CV PDF
                             </button>

                            <div class="sm:hidden fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur px-4 py-3 shadow-[0_-8px_24px_rgba(15,23,42,0.08)]">
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                    </svg>
                                    Buat CV PDF
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">1</div>
                            <h3 class="font-semibold text-gray-900">Lengkapi isi CV</h3>
                            <p class="text-sm text-gray-600 mt-2">Isi bagian penting seperti headline, ringkasan, pengalaman, dan posisi yang dituju.</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold mb-3">2</div>
                            <h3 class="font-semibold text-gray-900">Buat PDF</h3>
                            <p class="text-sm text-gray-600 mt-2">Sistem akan memakai satu template standar yang rapi dan konsisten.</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">3</div>
                            <h3 class="font-semibold text-gray-900">Unduh kapan saja</h3>
                            <p class="text-sm text-gray-600 mt-2">CV yang sudah dibuat tersimpan dan bisa diunduh ulang saat dibutuhkan.</p>
                        </div>
                    </div>
                </div>

                <aside class="space-y-5 lg:col-span-4 xl:col-span-3 lg:sticky lg:top-24 self-start">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">CV Anda</h3>
                        @if(session('success') && str_contains(session('success'), 'CV sedang diproses'))
                            {{-- CV sedang dibuat di queue: tampilkan skeleton + auto refresh --}}
                            <div x-data="{ seconds: 5 }" x-init="setInterval(() => { if (seconds > 0) seconds--; else window.location.reload(); }, 1000)">
                                <x-ui.skeleton-loader type="list" :count="1" />
                                <p class="mt-3 text-sm text-gray-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Sedang membuat PDF Anda… Halaman dimuat ulang otomatis dalam <span x-text="seconds"></span> detik.
                                </p>
                            </div>
                        @elseif($cvFiles->count() > 0)
                            <div class="space-y-3">
                                @foreach($cvFiles as $cv)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">CV {{ $cv->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Template standar · ATS friendly</p>
                                        </div>
                                        <div class="shrink-0 flex items-center gap-2">
                                            <a href="{{ route('cv.download', $cv->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">Unduh</a>
                                            <form action="{{ route('cv.destroy', $cv->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus CV ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center p-2 rounded-lg bg-red-100 text-red-600 text-xs font-semibold hover:bg-red-200 transition" title="Hapus CV">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-5 text-center">
                                <div class="w-14 h-14 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">Belum ada CV tersimpan</p>
                                <p class="text-sm text-gray-500 mt-1">Buat satu dulu, nanti hasilnya akan muncul di sini.</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-4">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <h3 class="text-sm font-bold text-gray-900">Preview CV</h3>
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-700 border border-blue-100">Standar</span>
                        </div>
                        <div class="preview-sheet preview-modern scale-[0.96] origin-top">
                            <div class="preview-hero">
                                <div class="preview-header">
                                    @if($previewData['name'])
                                        @if($previewData['name'])
                                            <div class="preview-avatar">{{ strtoupper(substr($previewData['name'], 0, 1)) }}</div>
                                        @endif
                                    @endif
                                    <div>
                                        <p class="preview-name">{{ $previewData['name'] }}</p>
                                        <p class="preview-headline" x-text="headline"></p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="preview-pill">ATS Friendly</span>
                                    <span class="preview-pill" style="margin-left:6px">Ringkas</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="preview-section">
                                    <div class="preview-section-title">Ringkasan</div>
                                    <div class="preview-section-body" x-text="summary"></div>
                                </div>
                                <div class="preview-section" style="margin-top:10px">
                                    <div class="preview-section-title">Data CV</div>
                                    <div class="preview-section-body">
                                        @if($previewData['linkedin_url']) LinkedIn · @endif
                                        @if($previewData['portfolio_url']) Portofolio · @endif
                                        <span x-text="headline"></span>
                                        @if(!empty($previewData['target_position']))
                                            <div class="mt-1 text-blue-700 font-semibold">Posisi: {{ $previewData['target_position'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="preview-section" style="margin-top:10px">
                                    <div class="preview-section-title">Skill</div>
                                    <div>
                                        @foreach(array_slice($previewData['skills'], 0, 4) as $skill)
                                            <span class="preview-skill">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                    @if(!empty($previewData['target_position']))
                                        <div class="preview-section-body mt-3">
                                            <div class="preview-section-title">Kata Kunci ATS</div>
                                            <div class="text-xs text-gray-600">{{ $previewData['target_position'] }}, administrasi, komunikasi, microsoft excel</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>



                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
