<x-app-layout>
    @push('styles')
    <style>[x-cloak] { display: none !important; }</style>
    @endpush
    <div x-data="{ template: 'modern' }" class="min-h-screen bg-[radial-gradient(circle_at_top,_#eff6ff_0,_#f8fafc_35%,_#eef2ff_100%)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between mb-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold mb-3">
                        ATS Ready · PDF Export · Template Fleksibel
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Pembuat CV</h1>
                    <p class="text-gray-600 mt-2 max-w-2xl">Buat CV yang lebih rapi, lebih padat isi, dan lebih enak dilihat perekrut. Jika profil kamu belum lengkap, halaman ini tetap memberi arahan yang jelas supaya hasil akhirnya tidak kosong.</p>
                </div>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-2xl bg-white border border-blue-100 px-4 py-3 shadow-sm">
                        <div class="text-lg font-bold text-gray-900">3</div>
                        <div class="text-xs text-gray-500">Template</div>
                    </div>
                    <div class="rounded-2xl bg-white border border-blue-100 px-4 py-3 shadow-sm">
                        <div class="text-lg font-bold text-gray-900">1x</div>
                        <div class="text-xs text-gray-500">Klik export</div>
                    </div>
                    <div class="rounded-2xl bg-white border border-blue-100 px-4 py-3 shadow-sm">
                        <div class="text-lg font-bold text-gray-900">PDF</div>
                        <div class="text-xs text-gray-500">Siap unduh</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h2 class="text-xl font-bold text-gray-900">Pilih Template</h2>
                            <p class="text-sm text-gray-600 mt-1">Pilih gaya yang paling cocok dengan tujuan lamaranmu.</p>
                        </div>

                        <form action="{{ route('cv.generate') }}" method="POST" class="p-6 sm:p-8">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_photo" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Foto profil
                                </label>
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_skills" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Tampilkan skill
                                </label>
                                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 shadow-sm">
                                    <input type="checkbox" name="include_certificates" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Tampilkan sertifikat
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <label class="cursor-pointer group" @click="template = 'modern'">
                                    <input type="radio" name="template" value="modern" class="peer hidden" x-model="template" checked>
                                    <div class="h-full rounded-2xl border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 p-4 transition shadow-sm group-hover:-translate-y-0.5 group-hover:shadow-md">
                                        <div class="rounded-xl bg-gradient-to-br from-blue-50 to-sky-100 p-4 h-44 mb-4 overflow-hidden">
                                            <div class="h-2 w-20 bg-blue-500 rounded-full mb-3"></div>
                                            <div class="h-2 w-32 bg-blue-200 rounded-full mb-2"></div>
                                            <div class="h-2 w-24 bg-blue-200 rounded-full mb-6"></div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="h-8 rounded-lg bg-white/85"></div>
                                                <div class="h-8 rounded-lg bg-white/65"></div>
                                            </div>
                                        </div>
                                        <p class="font-semibold text-gray-900">Modern</p>
                                        <p class="text-xs text-gray-500 mt-1">Tampilan bersih, cocok untuk CV yang terasa segar dan profesional.</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer group" @click="template = 'classic'">
                                    <input type="radio" name="template" value="classic" class="peer hidden" x-model="template">
                                    <div class="h-full rounded-2xl border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 p-4 transition shadow-sm group-hover:-translate-y-0.5 group-hover:shadow-md">
                                        <div class="rounded-xl bg-gradient-to-br from-gray-50 to-gray-200 p-4 h-44 mb-4 overflow-hidden">
                                            <div class="mx-auto h-16 w-16 rounded-full bg-gray-300 mb-3"></div>
                                            <div class="h-2 w-28 bg-gray-500 rounded-full mx-auto mb-2"></div>
                                            <div class="h-2 w-36 bg-gray-300 rounded-full mx-auto mb-6"></div>
                                            <div class="space-y-2">
                                                <div class="h-2 rounded-full bg-gray-300"></div>
                                                <div class="h-2 rounded-full bg-gray-300 w-5/6"></div>
                                            </div>
                                        </div>
                                        <p class="font-semibold text-gray-900">Klasik</p>
                                        <p class="text-xs text-gray-500 mt-1">Layout formal yang aman untuk rekrutmen tradisional dan instansi.</p>
                                    </div>
                                </label>

                                <label class="cursor-pointer group" @click="template = 'professional'">
                                    <input type="radio" name="template" value="professional" class="peer hidden" x-model="template">
                                    <div class="h-full rounded-2xl border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 p-4 transition shadow-sm group-hover:-translate-y-0.5 group-hover:shadow-md">
                                        <div class="rounded-xl bg-gradient-to-br from-indigo-50 to-purple-100 p-4 h-44 mb-4 overflow-hidden flex">
                                            <div class="w-16 rounded-xl bg-indigo-900/80 mr-3"></div>
                                            <div class="flex-1 space-y-2">
                                                <div class="h-3 w-24 bg-indigo-500 rounded-full"></div>
                                                <div class="h-2 w-32 bg-indigo-200 rounded-full"></div>
                                                <div class="h-2 w-28 bg-indigo-200 rounded-full mb-4"></div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="h-6 rounded-lg bg-white/90"></div>
                                                    <div class="h-6 rounded-lg bg-white/70"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="font-semibold text-gray-900">Profesional</p>
                                        <p class="text-xs text-gray-500 mt-1">Lebih tegas dan elegan untuk lowongan yang butuh kesan serius.</p>
                                    </div>
                                </label>
                            </div>

                            <div class="rounded-2xl bg-amber-50 border border-amber-100 px-4 py-3 text-sm text-amber-900 mb-6">
                                Tip: hasil terbaik muncul kalau profil kamu sudah lengkap. Isi nama, bio, pengalaman, skill, dan sertifikat terlebih dahulu.
                            </div>

                            <div class="grid grid-cols-1 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Headline CV</label>
                                    <input type="text" name="custom_headline" maxlength="120" placeholder="Contoh: Admin Office, Operator Produksi, Junior Web Developer" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Ringkasan singkat</label>
                                    <textarea name="custom_summary" rows="4" maxlength="1200" placeholder="Tulis 2-4 kalimat yang menjelaskan siapa kamu, keahlian utama, dan target kerja." class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Pengalaman / proyek paling relevan</label>
                                    <textarea name="custom_experience" rows="5" maxlength="2000" placeholder="Contoh:\n- Magang di toko retail selama 3 bulan\n- Membantu administrasi OSIS\n- Membuat website sekolah sederhana" class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-800 mb-2">Pencapaian utama</label>
                                    <textarea name="custom_achievement" rows="3" maxlength="500" placeholder="Contoh: Juara 2 lomba desain poster, lulus PKL dengan predikat baik, memimpin proyek kelas." class="w-full rounded-2xl border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/>
                                </svg>
                                Buat CV PDF
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold mb-3">1</div>
                            <h3 class="font-semibold text-gray-900">Pilih gaya</h3>
                            <p class="text-sm text-gray-600 mt-2">Tentukan template sesuai posisi dan karakter perusahaan.</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold mb-3">2</div>
                            <h3 class="font-semibold text-gray-900">Unduh PDF</h3>
                            <p class="text-sm text-gray-600 mt-2">CV tersimpan rapi dan bisa diunduh lagi kapan saja.</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold mb-3">3</div>
                            <h3 class="font-semibold text-gray-900">Perbarui terus</h3>
                            <p class="text-sm text-gray-600 mt-2">Sesuaikan template saat profil atau pengalaman kamu bertambah.</p>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">CV Anda</h3>
                        @if($cvFiles->count() > 0)
                            <div class="space-y-3">
                                @foreach($cvFiles as $cv)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">CV {{ $cv->created_at->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $cv->is_ats_friendly ? 'ATS friendly' : 'Template standar' }}</p>
                                        </div>
                                        <a href="{{ route('cv.download', $cv->id) }}" class="shrink-0 inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">Unduh</a>
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

                    <div class="bg-gradient-to-br from-slate-900 to-blue-900 text-white rounded-3xl p-6 shadow-xl">
                        <h3 class="text-lg font-bold mb-3">Agar CV tidak kosong</h3>
                        <ul class="space-y-3 text-sm text-blue-100">
                            <li>• Isi ringkasan singkat di profil.</li>
                            <li>• Tambahkan skill utama yang relevan.</li>
                            <li>• Sertakan pengalaman dan sertifikat.</li>
                            <li>• Foto opsional, tapi bantu identitas jika rapi.</li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Cepat</h3>
                        <div x-show="template === 'modern'" x-cloak class="rounded-3xl bg-slate-900 text-white overflow-hidden shadow-lg">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-xl font-bold">{{ strtoupper(substr($previewData['name'], 0, 1)) }}</div>
                                    <div>
                                        <p class="font-bold text-lg leading-tight">{{ $previewData['name'] }}</p>
                                        <p class="text-sm text-blue-100">{{ $previewData['headline'] }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2 text-xs text-white/90">
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/15">ATS Friendly</span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/15">Ringkas</span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/15">Siap Kirim</span>
                                </div>
                            </div>
                            <div class="p-5 space-y-4 text-sm">
                                <div>
                                    <p class="text-blue-200 text-xs uppercase tracking-widest mb-2">Ringkasan</p>
                                    <p class="text-white/90 leading-relaxed">{{ $previewData['summary'] }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-200 text-xs uppercase tracking-widest mb-2">Skill</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice($previewData['skills'], 0, 4) as $skill)
                                            <span class="px-3 py-1 rounded-full bg-white/10 border border-white/15">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="template === 'classic'" x-cloak class="rounded-3xl bg-white border border-gray-200 overflow-hidden shadow-lg">
                            <div class="px-5 py-4 text-center border-b border-gray-200">
                                <p class="text-xl font-bold text-gray-900">{{ strtoupper($previewData['name']) }}</p>
                                <p class="text-xs tracking-[0.24em] text-gray-500 mt-1">{{ $previewData['headline'] }}</p>
                            </div>
                            <div class="p-5 space-y-4 text-sm text-gray-700">
                                <div>
                                    <p class="font-semibold text-gray-900 mb-1">Profil Singkat</p>
                                    <p class="leading-relaxed">{{ $previewData['summary'] }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 mb-2">Pendidikan</p>
                                    <p>{{ $previewData['education']['school'] }}</p>
                                    <p>{{ $previewData['education']['major'] }}</p>
                                    <p>{{ $previewData['education']['year'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div x-show="template === 'professional'" x-cloak class="rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-white">
                            <div class="grid grid-cols-[110px,1fr] min-h-[220px]">
                                <div class="bg-slate-900 text-white p-4 flex flex-col justify-between">
                                    <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-xl font-bold">{{ strtoupper(substr($previewData['name'], 0, 1)) }}</div>
                                    <div>
                                        <p class="font-semibold leading-tight">{{ $previewData['email'] }}</p>
                                        <p class="text-xs text-slate-300 mt-2">{{ $previewData['phone'] }}</p>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <p class="text-lg font-bold text-slate-900">{{ $previewData['name'] }}</p>
                                    <p class="text-sm text-slate-500 mt-1">{{ $previewData['headline'] }}</p>
                                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                                        <div>
                                            <p class="font-semibold text-slate-900">Tentang Saya</p>
                                            <p class="leading-relaxed">{{ $previewData['summary'] }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">Pendidikan</p>
                                            <p>{{ $previewData['education']['school'] }} · {{ $previewData['education']['major'] }} · {{ $previewData['education']['year'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
