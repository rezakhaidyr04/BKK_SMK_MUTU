<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Acara Karir</h1>
                        <p class="text-purple-100">Job fair, workshop, seminar, dan kegiatan pengembangan karir.</p>
                    </div>
                    @auth
                    <a href="{{ route('events.my') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Acara Saya
                    </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                <form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap gap-3 items-center">
                    <a href="{{ route('events.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ !request('filter') && !request('type') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'upcoming']) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request('filter') === 'upcoming' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Akan Datang
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'past']) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request('filter') === 'past' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Sudah Selesai
                    </a>
                    <div class="w-px bg-gray-200 h-6 mx-1"></div>
                    @foreach(['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan'] as $val=>$label)
                    <a href="{{ route('events.index', ['type' => $val]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request('type') === $val ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </form>
            </div>

            @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                @php
                    $isPast = $event->start_time->isPast();
                    $isRegistered = in_array($event->id, $registeredIds);
                    $typeColors = ['job_fair'=>'bg-blue-100 text-blue-700','seminar'=>'bg-purple-100 text-purple-700','workshop'=>'bg-orange-100 text-orange-700','pelatihan'=>'bg-green-100 text-green-700','lainnya'=>'bg-gray-100 text-gray-700'];
                    $typeLabels = ['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan','lainnya'=>'Lainnya'];
                    $gradients  = ['job_fair'=>'from-blue-500 to-indigo-600','seminar'=>'from-purple-500 to-pink-600','workshop'=>'from-orange-500 to-red-500','pelatihan'=>'from-green-500 to-teal-600','lainnya'=>'from-gray-500 to-gray-600'];
                @endphp
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col {{ $isPast ? 'opacity-75' : '' }}">
                    <!-- Thumbnail / Poster -->
                    @if($event->poster)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->title }}"
                             class="w-full h-44 object-cover">
                        @if($isPast)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <span class="bg-white/90 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Sudah Selesai</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="relative h-44 bg-gradient-to-br {{ $gradients[$event->type] ?? 'from-indigo-500 to-purple-600' }} flex items-center justify-center">
                        <svg class="text-white/70 ui-svg-icon ui-svg-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @if($isPast)
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                            <span class="bg-white/90 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Sudah Selesai</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $typeColors[$event->type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $typeLabels[$event->type] ?? $event->type }}
                            </span>
                            @if($isRegistered)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 flex items-center gap-1">
                                <svg class="ui-svg-icon ui-svg-icon-sm" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Terdaftar
                            </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 flex-1">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $event->title }}
                            </a>
                        </h3>

                        <div class="space-y-1.5 text-xs text-gray-500 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="text-gray-400 flex-shrink-0 ui-svg-icon ui-svg-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $event->start_time->format('d M Y, H:i') }} WIB
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="text-gray-400 flex-shrink-0 ui-svg-icon ui-svg-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ Str::limit($event->location, 35) }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="text-gray-400 flex-shrink-0 ui-svg-icon ui-svg-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $event->registrations_count }} peserta terdaftar
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex gap-2 mt-auto">
                            <a href="{{ route('events.show', $event) }}"
                               class="flex-1 text-center px-4 py-2 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                                Detail
                            </a>
                            @if(!$isPast)
                                @auth
                                    @if($isRegistered)
                                    <form method="POST" action="{{ route('events.cancel', $event) }}"
                                          onsubmit="return confirm('Batalkan pendaftaran?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('events.register', $event) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                                            Daftar
                                        </button>
                                    </form>
                                    @endif
                                @else
                                <a href="{{ route('login') }}"
                                   class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                                    Daftar
                                </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $events->links() }}</div>
            @else
            <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="text-indigo-500 ui-svg-icon ui-svg-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada acara</p>
                <p class="text-gray-400 text-sm mt-1">Acara karir akan ditampilkan di sini setelah dijadwalkan.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
