<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner 
            title="{{ $event->title }}" 
            subtitle="Detail acara karir dan pendaftaran."
            :back-url="route('events.index')"
            back-label="Kembali ke Acara" eyebrow="Beranda › Acara">
            <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \App\Support\Label::eventType($event->type) ?? ucfirst($event->type) }}
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ $event->location }} · {{ $event->start_time->format('d M Y') }}
                </span>
            </x-slot:chips>
        </x-ui.page-banner>
        <div class="page-container page-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Konten Utama -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}"
                             class="w-full h-64 object-cover">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif

                        <div class="p-6 sm:p-8">
                            @php
                                $typeLabels = ['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan','lainnya'=>'Lainnya'];
                                $typeColors = ['job_fair'=>'bg-blue-100 text-blue-700','seminar'=>'bg-violet-100 text-violet-700','workshop'=>'bg-amber-100 text-amber-700','pelatihan'=>'bg-green-100 text-green-700','lainnya'=>'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $typeColors[$event->type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $typeLabels[$event->type] ?? $event->type }}
                            </span>

                            <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $event->title }}</h1>

                            <!-- Detail Info -->
                            <dl class="mt-6 grid gap-4 sm:grid-cols-2 bg-gray-50 rounded-xl p-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Tanggal & Waktu</dt>
                                        <dd class="mt-0.5 text-sm font-semibold text-gray-900">{{ $event->start_time->format('d M Y') }}</dd>
                                        <dd class="text-xs text-gray-600">
                                            {{ $event->start_time->format('H:i') }}
                                            @if($event->end_time) – {{ $event->end_time->format('H:i') }} WIB @endif
                                        </dd>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Lokasi</dt>
                                        <dd class="mt-0.5 text-sm font-semibold text-gray-900">{{ $event->location }}</dd>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Peserta Terdaftar</dt>
                                        <dd class="mt-0.5 text-sm font-semibold text-gray-900">{{ $event->registrations_count }} @if($event->quota)/ {{ $event->quota }} @endif orang</dd>
                                        @if($event->quota && $event->registrations_count >= $event->quota)
                                            <dd class="text-xs text-red-600 font-medium">Kuota penuh</dd>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 {{ $event->isPaid() ? 'bg-amber-100' : 'bg-emerald-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 {{ $event->isPaid() ? 'text-amber-600' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002 2v2a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Biaya</dt>
                                        @if($event->isPaid())
                                            <dd class="mt-0.5 text-sm font-bold text-amber-700">Rp {{ number_format($event->price,0,',','.') }}</dd>
                                            <dd class="text-xs text-amber-600">Berbayar</dd>
                                        @else
                                            <dd class="mt-0.5 text-sm font-bold text-emerald-600">Gratis</dd>
                                            <dd class="text-xs text-gray-500">Tidak dipungut biaya</dd>
                                        @endif
                                    </div>
                                </div>
                            </dl>
                            @if($event->isPaid() && $event->payment_instructions)
                                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4">
                                    <p class="text-xs font-bold text-amber-800 flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Instruksi Pembayaran</p>
                                    <p class="text-sm text-amber-900 mt-1 whitespace-pre-line">{{ $event->payment_instructions }}</p>
                                </div>
                            @endif

                            <!-- Deskripsi -->
                            <div class="mt-6 prose max-w-none text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                                {{ $event->description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Pendaftaran -->
                <div class="space-y-4">
                    <!-- Card Daftar -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-24">
                        <div class="bg-gradient-to-r {{ $event->isPaid() ? 'from-amber-500 to-orange-600' : 'from-blue-600 to-violet-600' }} px-6 py-4">
                            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                                @if($event->isPaid())
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2h2"/></svg>
                                    Acara Berbayar
                                @else
                                    Daftar Acara
                                @endif
                            </h3>
                            <p class="text-white/90 text-sm mt-0.5">
                                @if($event->isPaid())
                                    Rp {{ number_format($event->price,0,',','.') }} · {{ $event->quota ? $event->quota.' kuota' : 'Tanpa batas' }}
                                @else
                                    Gratis · Amankan tempat kamu sekarang
                                @endif
                            </p>
                        </div>
                        <div class="p-6">
                            @if(session('success'))
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                            @endif
                            @if(session('error'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                            @endif

                            @if($event->start_time->isPast())
                                <div class="text-center py-4">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium text-sm">Acara ini sudah selesai</p>
                                    <p class="text-gray-400 text-xs mt-1">Pendaftaran telah ditutup</p>
                                </div>
                            @elseif($event->quota && $event->registrations_count >= $event->quota && (!$registration || $registration->status !== 'registered'))
                                <div class="text-center py-4">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-red-600 font-bold text-sm">Kuota Penuh</p>
                                    <p class="text-gray-400 text-xs mt-1">Semua kursi sudah terisi</p>
                                </div>
                            @elseif(!Auth::check())
                                @if($event->isPaid())
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4 flex items-start gap-2">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="text-xs text-amber-800 leading-relaxed">
                                            <p class="font-bold">Rp {{ number_format($event->price,0,',','.') }}</p>
                                            <p class="mt-1">Login & daftar, lalu upload bukti transfer sesuai instruksi.</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <p class="text-sm text-gray-600 mb-4">Login untuk mendaftar ke acara ini</p>
                                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">Login untuk Daftar</a>
                                </div>
                            @elseif($registration && $registration->status === 'registered')
                                @if(!$event->isPaid())
                                    <div class="text-center mb-4">
                                        <div class="w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-green-700 font-bold">Kamu sudah terdaftar!</p>
                                        <p class="text-gray-500 text-xs mt-1">Terdaftar pada {{ $registration->registered_at->format('d M Y, H:i') }}</p>
                                        <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold border border-green-200">Gratis · Terkonfirmasi</span>
                                    </div>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}" data-confirm="Batalkan pendaftaran acara ini?" data-confirm-title="Batalkan" data-confirm-ok="Batalkan" data-confirm-variant="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-5 py-2.5 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">Batalkan Pendaftaran</button>
                                    </form>
                                @elseif($registration->payment_status === 'verified')
                                    <div class="text-center mb-4">
                                        <div class="w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-green-700 font-bold">Pembayaran Terverifikasi!</p>
                                        <p class="text-gray-500 text-xs mt-1">Lunas pada {{ $registration->paid_at?->format('d M Y, H:i') ?? $registration->registered_at->format('d M Y') }}</p>
                                        <span class="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full bg-green-600 text-white text-xs font-bold">✓ Peserta Resmi</span>
                                    </div>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}" data-confirm="Batalkan pendaftaran acara ini?" data-confirm-title="Batalkan" data-confirm-ok="Batalkan" data-confirm-variant="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-5 py-2.5 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">Batalkan Pendaftaran</button>
                                    </form>
                                @elseif($registration->payment_status === 'pending')
                                    <div class="text-center mb-4">
                                        <div class="w-12 h-12 mx-auto mb-3 bg-amber-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-amber-700 font-bold">Menunggu Verifikasi</p>
                                        <p class="text-gray-500 text-xs mt-1">Bukti sudah dikirim, admin akan verifikasi 1–2 jam kerja.</p>
                                        @if($registration->payment_proof)
                                            <a href="{{ asset('storage/' . $registration->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-blue-600 hover:underline">Lihat bukti terkirim →</a>
                                        @endif
                                    </div>
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-xs text-blue-800 mb-3">Jika ada kendala hubungi panitia via menu Pesan.</div>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}" data-confirm="Batalkan pendaftaran acara ini?" data-confirm-title="Batalkan" data-confirm-ok="Batalkan" data-confirm-variant="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-5 py-2.5 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">Batalkan Pendaftaran</button>
                                    </form>
                                @elseif($registration->payment_status === 'rejected')
                                    <div class="text-center mb-4">
                                        <div class="w-12 h-12 mx-auto mb-3 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-red-700 font-bold">Pembayaran Ditolak</p>
                                        <p class="text-gray-500 text-xs mt-1">Bukti tidak valid / buram. Silakan upload ulang bukti yang jelas.</p>
                                    </div>
                                    <form method="POST" action="{{ route('events.payment-proof', $event) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Upload Ulang Bukti Transfer</label>
                                            <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 file:font-semibold cursor-pointer border border-slate-200 rounded-xl p-1">
                                        </div>
                                        <button type="submit" class="w-full px-5 py-2.5 bg-amber-600 text-white text-sm font-bold rounded-xl hover:bg-amber-700 transition">Kirim Ulang Bukti</button>
                                    </form>
                                @else {{-- unpaid --}}
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                                        <p class="text-xs font-bold text-amber-800">Tagihan: Rp {{ number_format($event->price,0,',','.') }}</p>
                                        @if($event->payment_instructions)
                                            <p class="text-xs text-amber-900 mt-2 whitespace-pre-line leading-relaxed">{{ $event->payment_instructions }}</p>
                                        @else
                                            <p class="text-xs text-amber-800 mt-1">Silakan transfer sesuai nominal di atas dan upload bukti di bawah.</p>
                                        @endif
                                        <p class="text-xs text-amber-700 mt-2">Status: <span class="font-bold">Belum bayar</span> · Daftar pada {{ $registration->registered_at->format('d M Y') }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('events.payment-proof', $event) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                                            <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold cursor-pointer border border-slate-200 rounded-xl p-1">
                                            <p class="text-xs text-slate-400 mt-1">JPG/PNG/WebP maks 4MB, pastikan nominal terlihat jelas.</p>
                                        </div>
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow">Upload Bukti Pembayaran</button>
                                    </form>
                                    <form method="POST" action="{{ route('events.cancel', $event) }}" class="mt-3" data-confirm="Batalkan pendaftaran acara ini?" data-confirm-title="Batalkan" data-confirm-ok="Batalkan" data-confirm-variant="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-5 py-2.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl hover:bg-slate-50 transition">Batalkan Pendaftaran</button>
                                    </form>
                                @endif
                            @else
                                @if($event->isPaid())
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4 flex items-start gap-2">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div class="text-xs text-amber-800 leading-relaxed">
                                            <p class="font-bold">Biaya: Rp {{ number_format($event->price,0,',','.') }}</p>
                                            <p class="mt-1">Setelah klik Daftar, kamu akan diminta upload bukti transfer.</p>
                                            @if($event->quota)<p class="mt-1 text-amber-700">Sisa kuota: {{ max(0, $event->quota - $event->registrations_count) }} kursi</p>@endif
                                        </div>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('events.register', $event) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama</label>
                                        <input type="text" value="{{ Auth::user()->name }}" disabled class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                                        <textarea name="notes" rows="2" placeholder="Pertanyaan atau informasi tambahan..." class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 {{ $event->isPaid() ? 'bg-amber-600 hover:bg-amber-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white text-sm font-bold rounded-xl transition shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $event->isPaid() ? 'Daftar & Bayar' : 'Daftar Sekarang' }}
                                    </button>
                                    @if($event->isPaid() && $event->payment_instructions)
                                        <p class="text-xs text-slate-500 mt-3 text-center leading-relaxed">Dengan mendaftar kamu menyetujui instruksi pembayaran di atas.</p>
                                    @endif
                                </form>
                            @endif

                            @auth
                            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                                <a href="{{ route('events.my') }}" class="text-xs text-blue-600 hover:underline">
                                    Lihat semua acara yang kamu ikuti →
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
