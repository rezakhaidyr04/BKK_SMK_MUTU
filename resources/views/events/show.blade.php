<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-700 mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Acara
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Konten Utama -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        @if($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}"
                             class="w-full h-64 object-cover">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif

                        <div class="p-6 sm:p-8">
                            @php
                                $typeLabels = ['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan','lainnya'=>'Lainnya'];
                                $typeColors = ['job_fair'=>'bg-blue-100 text-blue-700','seminar'=>'bg-purple-100 text-purple-700','workshop'=>'bg-orange-100 text-orange-700','pelatihan'=>'bg-green-100 text-green-700','lainnya'=>'bg-gray-100 text-gray-700'];
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
                                        <dd class="mt-0.5 text-sm font-semibold text-gray-900">{{ $event->registrations_count }} orang</dd>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500">Status</dt>
                                        @if($event->start_time->isFuture())
                                        <dd class="mt-0.5 text-sm font-semibold text-green-600">Akan Datang</dd>
                                        @else
                                        <dd class="mt-0.5 text-sm font-semibold text-gray-500">Sudah Selesai</dd>
                                        @endif
                                    </div>
                                </div>
                            </dl>

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
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h3 class="text-white font-bold text-lg">Daftar Acara</h3>
                            <p class="text-indigo-100 text-sm mt-0.5">Amankan tempat kamu sekarang</p>
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
                                <!-- Acara sudah selesai -->
                                <div class="text-center py-4">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium text-sm">Acara ini sudah selesai</p>
                                    <p class="text-gray-400 text-xs mt-1">Pendaftaran telah ditutup</p>
                                </div>

                            @elseif(!Auth::check())
                                <!-- Belum login -->
                                <div class="text-center">
                                    <p class="text-sm text-gray-600 mb-4">Login untuk mendaftar ke acara ini</p>
                                    <a href="{{ route('login') }}"
                                       class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                                        Login untuk Daftar
                                    </a>
                                </div>

                            @elseif($registration && $registration->status === 'registered')
                                <!-- Sudah terdaftar -->
                                <div class="text-center mb-4">
                                    <div class="w-12 h-12 mx-auto mb-3 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-green-700 font-bold">Kamu sudah terdaftar!</p>
                                    <p class="text-gray-500 text-xs mt-1">Terdaftar pada {{ $registration->registered_at->format('d M Y, H:i') }}</p>
                                </div>
                                <form method="POST" action="{{ route('events.cancel', $event) }}"
                                      onsubmit="return confirm('Batalkan pendaftaran acara ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-5 py-2.5 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">
                                        Batalkan Pendaftaran
                                    </button>
                                </form>

                            @else
                                <!-- Form Daftar -->
                                <form method="POST" action="{{ route('events.register', $event) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama</label>
                                        <input type="text" value="{{ Auth::user()->name }}" disabled
                                               class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                                        <textarea name="notes" rows="2" placeholder="Pertanyaan atau informasi tambahan..."
                                                  class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('notes') }}</textarea>
                                    </div>
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Daftar Sekarang
                                    </button>
                                </form>
                            @endif

                            @auth
                            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                                <a href="{{ route('events.my') }}" class="text-xs text-indigo-600 hover:underline">
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
