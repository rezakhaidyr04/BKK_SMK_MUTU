<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Hubungi Kami" subtitle="Pertanyaan akun, kemitraan perusahaan, atau laporan lowongan — kami balas maks. 2 hari kerja." eyebrow="BKKMu › Kontak">
            <x-slot:chips>
                <span class="page-banner__chip">Respon cepat</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section grid gap-6 lg:grid-cols-5 max-w-5xl mx-auto">
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4 h-fit">
                <h2 class="font-bold text-gray-900">Info kontak</h2>
                <div class="text-sm text-gray-600 space-y-3">
                    <p class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        SMK TI Muhammadiyah Cikampek, Cikampek, Jawa Barat
                    </p>
                    <p>
                        <a href="mailto:bkksmkmutu3@gmail.com" class="flex items-center gap-2 text-blue-600 hover:underline">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            bkksmkmutu3@gmail.com
                        </a>
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        (0267) 123-456
                    </p>
                </div>
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-800 leading-relaxed">
                    Menemukan lowongan mencurigakan? Pilih subjek <strong>Laporan lowongan</strong> dan sertakan tautan lowongannya — jangan transfer uang ke pihak mana pun.
                </div>
                <div class="text-xs text-gray-400">
                    Butuh jawaban cepat? Cek <a href="{{ url('/faq') }}" class="text-blue-600 underline">FAQ</a> dulu.
                </div>
            </div>

            <div class="lg:col-span-3">
                <x-ui.form-errors />
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
                @endif
                <form action="{{ route('contact.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Nama <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required maxlength="100" value="{{ old('name', auth()->user()->name ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required maxlength="100" value="{{ old('email', auth()->user()->email ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-semibold text-gray-900 mb-2">Subjek <span class="text-red-500">*</span></label>
                        <select name="subject" id="subject" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                            <option value="">— Pilih —</option>
                            @foreach(['Pertanyaan akun', 'Kemitraan perusahaan', 'Laporan lowongan', 'Kendala teknis', 'Lainnya'] as $opt)
                                <option value="{{ $opt }}" @selected(old('subject') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">Pesan <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="6" required maxlength="2000" placeholder="Tulis detail: nama akun, tautan lowongan, tangkapan layar, dsb."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-400">Min. 10 karakter, maks. 2000 karakter.</p>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
