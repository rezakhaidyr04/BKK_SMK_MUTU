<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Acara Saya</h1>
                        <p class="text-purple-100">Acara yang kamu daftarkan</p>
                    </div>
                    <a href="{{ route('events.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        Jelajahi Acara Lain
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @forelse($registrations as $reg)
            @php
                $event = $reg->event;
                $isPast = $event->start_time->isPast();
                $typeColors = ['job_fair'=>'bg-blue-100 text-blue-700','seminar'=>'bg-purple-100 text-purple-700','workshop'=>'bg-orange-100 text-orange-700','pelatihan'=>'bg-green-100 text-green-700','lainnya'=>'bg-gray-100 text-gray-700'];
                $typeLabels = ['job_fair'=>'Job Fair','seminar'=>'Seminar','workshop'=>'Workshop','pelatihan'=>'Pelatihan','lainnya'=>'Lainnya'];
            @endphp
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-4 {{ $reg->status === 'cancelled' ? 'opacity-60' : '' }}">
                <div class="flex flex-col sm:flex-row">
                    @if($event->poster)
                    <img src="{{ asset('storage/' . $event->poster) }}" alt="" class="w-full sm:w-36 h-36 object-cover flex-shrink-0">
                    @else
                    <div class="w-full sm:w-36 h-36 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-10 h-10 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1 p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$event->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $typeLabels[$event->type] ?? $event->type }}
                                </span>
                                @if($reg->status === 'cancelled')
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Dibatalkan</span>
                                @elseif($isPast)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Selesai</span>
                                @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Terdaftar</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-900">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                📅 {{ $event->start_time->format('d M Y, H:i') }} &nbsp;·&nbsp;
                                📍 {{ $event->location }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Didaftarkan: {{ $reg->registered_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('events.show', $event) }}"
                               class="px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition">
                                Detail
                            </a>
                            @if($reg->status === 'registered' && !$isPast)
                            <form method="POST" action="{{ route('events.cancel', $event) }}"
                                  onsubmit="return confirm('Batalkan pendaftaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-indigo-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada acara</h3>
                <p class="text-gray-500 text-sm mb-6">Kamu belum mendaftar ke acara apapun.</p>
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                    Jelajahi Acara
                </a>
            </div>
            @endforelse

            @if($registrations->hasPages())
            <div class="mt-6">{{ $registrations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
