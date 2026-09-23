<x-app-layout :full-bleed="true" title="Acara Karir — BKK SMK MUTU" description="Job fair, workshop, seminar, dan kegiatan pengembangan karir dari BKK SMK MUTU.">
    <div class="page-shell">
        <x-ui.page-banner title="Acara Karir" subtitle="Job fair, workshop, seminar, dan kegiatan pengembangan karir." eyebrow="Beranda › Acara">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Job Fair · Workshop · Seminar
                </span>
                <span class="page-banner__chip">{{ $events->total() ?? $events->count() }} Acara</span>
            </x-slot:chips>
            <x-slot:actions>
                @auth
                <x-ui.btn href="{{ route('events.my') }}" variant="secondary" size="sm">Acara Saya</x-ui.btn>
                @endauth
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">
            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                <form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap gap-3 items-center">
                    <a href="{{ route('events.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ !request('filter') && !request('type') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'upcoming']) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request('filter') === 'upcoming' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Akan Datang
                    </a>
                    <a href="{{ route('events.index', ['filter' => 'past']) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ request('filter') === 'past' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Sudah Selesai
                    </a>
                    <div class="w-px bg-gray-200 h-6 mx-1"></div>
                    @foreach(['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan'] as $val=>$label)
                    <a href="{{ route('events.index', ['type' => $val]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium transition {{ request('type') === $val ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
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
                    $typeColors = ['job_fair'=>'bg-blue-100 text-blue-700','seminar'=>'bg-violet-100 text-violet-700','workshop'=>'bg-amber-100 text-amber-700','pelatihan'=>'bg-green-100 text-green-700','lainnya'=>'bg-gray-100 text-gray-700'];
                    $typeLabels = ['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan','lainnya'=>'Lainnya'];
                    $gradients  = ['job_fair'=>'from-blue-500 to-blue-600','seminar'=>'from-violet-500 to-violet-600','workshop'=>'from-amber-500 to-red-500','pelatihan'=>'from-green-500 to-green-600','lainnya'=>'from-gray-500 to-gray-600'];
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
                    <div class="relative h-44 bg-gradient-to-br {{ $gradients[$event->type] ?? 'from-blue-500 to-violet-600' }} flex items-center justify-center">
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

                    <div class="p-5 flex flex-col flex-1">
                        {{-- Badges: fixed 2-row area, always same height --}}
                        <div class="flex flex-wrap items-center gap-1.5 min-h-[28px] mb-2.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $typeColors[$event->type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $typeLabels[$event->type] ?? $event->type }}
                            </span>
                            @if($event->is_paid)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">Rp {{ number_format($event->price,0,',','.') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Gratis</span>
                            @endif
                            @if($isRegistered)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Terdaftar
                                </span>
                            @endif
                        </div>
                        @if($event->quota)
                            <p class="text-[11px] font-medium text-slate-400 -mt-1 mb-2 tracking-wide">{{ $event->registrations_count }}/{{ $event->quota }} kuota terisi</p>
                        @else
                            <p class="text-[11px] font-medium text-slate-400 -mt-1 mb-2 tracking-wide">&nbsp;</p>
                        @endif

                        {{-- Title: fixed 2-line height --}}
                        <h3 class="font-bold text-[15px] leading-snug text-slate-900 min-h-[44px] line-clamp-2 mb-3">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600 transition-colors">
                                {{ $event->title }}
                            </a>
                        </h3>

                        {{-- Info: fixed height --}}
                        <div class="space-y-2 text-xs text-slate-500 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <span class="truncate">{{ $event->start_time->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </span>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <span>{{ $event->registrations_count }} peserta terdaftar</span>
                            </div>
                        </div>

                        {{-- Actions: hanya Detail, daftar via halaman detail --}}
                        <div class="mt-auto pt-3 border-t border-slate-100">
                            <a href="{{ route('events.show', $event) }}" class="block w-full text-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 transition">Detail</a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $events->links() }}</div>
            @else
            <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="text-blue-500 ui-svg-icon ui-svg-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
