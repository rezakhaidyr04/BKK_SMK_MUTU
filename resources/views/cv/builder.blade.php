<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner
            title="Buat CV"
            subtitle="CV dibuat otomatis dari data profil Anda — rapi, konsisten, siap dikirim ke perekrut."
            eyebrow="ATS Ready · PDF Export · 1 Template"
        >
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    ATS Friendly
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF Export · {{ $cvFiles->count() }} CV Tersimpan
                </span>
            </x-slot:chips>
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

        <div x-data="{}" class="page-container page-section">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <form action="{{ route('cv.build') }}" method="POST" class="lg:col-span-8 xl:col-span-9 space-y-6">
                    @csrf
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-blue-50">
                            <h2 class="text-xl font-bold text-gray-900">Buat CV</h2>
                            <p class="text-sm text-gray-600 mt-1">Isi data di bawah sekali — otomatis tersimpan ke profil — lalu buat PDF siap ATS.</p>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-4 mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shrink-0">CV</div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-900">Template standar aktif</p>
                                        <p class="text-sm text-gray-600 mt-1">Satu template rapi dan konsisten untuk semua CV — datanya diambil dari isian karier di kartu ini juga.</p>
                                    </div>
                                </div>
                            </div>

                            @include('profile.partials.career-fields', ['user' => $user])

                            <hr class="my-6 border-gray-100">

                            <div class="cv-form-card pb-28 sm:pb-8 space-y-5">
                            <input type="hidden" name="template" value="modern">
                            <input type="hidden" name="include_skills" value="1">
                            <input type="hidden" name="include_certificates" value="1">

                            <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-sm text-amber-900">
                                Tip: bio, pengalaman, dan skill yang kosong bikin CV tipis — isi sekalian di kartu ini.
                            </div>

                            <div class="grid grid-cols-1 gap-3.5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Pencapaian utama <span class="font-normal text-gray-400">(opsional)</span></label>
                                    <textarea name="custom_achievement" rows="2" maxlength="500" placeholder="Contoh: Juara 2 lomba desain poster, lulus PKL dengan predikat baik, memimpin proyek kelas." class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('custom_achievement') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1.5">Tidak punya? Kosongkan saja — bagian ini tidak akan tampil di CV.</p>
                                </div>
                            </div>

                            <button type="submit" class="hidden sm:inline-flex w-full items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                 </svg>
                                 Simpan & Buat CV PDF
                             </button>

                            <div class="sm:hidden fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur px-4 pt-3 shadow-[0_-8px_24px_rgba(15,23,42,0.08)]" style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));">
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[50px] bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition shadow-lg shadow-blue-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                    </svg>
                                    Simpan & Buat CV PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>

                </form>

                <aside class="space-y-5 lg:col-span-4 xl:col-span-3 lg:sticky lg:top-24 self-start">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">CV Anda</h3>
                        @if(($cvFailed ?? false) && !($cvGenerating ?? false))
                            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 mb-3">
                                <p class="text-sm font-semibold text-red-800">Pembuatan CV gagal.</p>
                                <p class="text-sm text-red-600 mt-1">CV Anda yang lama tetap aman. Silakan coba buat lagi.</p>
                            </div>
                        @endif
                        @if(($cvGenerating ?? false) || (session('success') && str_contains(session('success'), 'CV sedang diproses')))
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
                                            <form action="{{ route('cv.destroy', $cv->id) }}" method="POST" class="inline-block" data-confirm="Apakah Anda yakin ingin menghapus CV ini?" data-confirm-title="Hapus" data-confirm-ok="Hapus" data-confirm-variant="danger">
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
                        <div class="preview-sheet bg-white text-black scale-[0.96] origin-top" style="font-family:Georgia,'Times New Roman',serif;">
                            <div style="text-align:center;margin-bottom:10px;">
                                <p style="font-size:1rem;font-weight:800;text-transform:uppercase;color:#111;">{{ $previewData['name'] }}</p>
                                <p style="font-size:.62rem;color:#111;">{{ $previewData['address'] }} | HP: {{ $previewData['phone'] }} | Email: {{ $previewData['email'] }}</p>
                            </div>
                            <div style="margin-top:10px;">
                                <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;color:#111;border-bottom:1.5px solid #111;padding-bottom:2px;margin-bottom:6px;">Ringkasan</div>
                                <div style="font-size:.66rem;line-height:1.55;color:#222;">{{ $previewData['summary'] }}</div>
                            </div>
                            <div style="margin-top:10px;">
                                <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;color:#111;border-bottom:1.5px solid #111;padding-bottom:2px;margin-bottom:6px;">Pengalaman</div>
                                <div style="font-size:.66rem;line-height:1.55;color:#222;white-space:pre-line;">{{ $previewData['experience'] }}</div>
                            </div>
                            <div style="margin-top:10px;">
                                <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;color:#111;border-bottom:1.5px solid #111;padding-bottom:2px;margin-bottom:6px;">Pendidikan</div>
                                <div style="font-size:.66rem;line-height:1.55;color:#222;white-space:pre-line;">{{ $previewData['education']['history'] }}</div>
                            </div>
                            <div style="margin-top:10px;">
                                <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;color:#111;border-bottom:1.5px solid #111;padding-bottom:2px;margin-bottom:6px;">Kemampuan</div>
                                <ul style="font-size:.66rem;line-height:1.55;color:#222;margin:0;padding-left:18px;list-style:disc;">
                                    @foreach(array_slice($previewData['skills'], 0, 6) as $skill)
                                        <li>{{ $skill }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>



                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
