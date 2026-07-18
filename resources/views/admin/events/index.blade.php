<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Manajemen Acara" subtitle="Kelola acara karir, job fair, dan seminar untuk siswa & alumni.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.events.create') }}" size="sm">
                    Buat Acara
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-ui.stat-card label="Total Acara" :value="$events->total()" icon="calendar" color="blue" />
        <x-ui.stat-card label="Acara Mendatang" :value="\App\Models\Event::where('start_time', '>=', now())->count()" icon="clock" color="green" />
        <x-ui.stat-card label="Acara Selesai" :value="\App\Models\Event::where('start_time', '<', now())->count()" icon="check" color="slate" />
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Acara</th>
                        <th>Tipe</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($event->poster)
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-slate-200">
                                @else
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0 border border-indigo-100">
                                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($event->description, 55) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $typeLabels = [
                                    'job_fair'  => 'Job Fair',
                                    'seminar'   => 'Seminar',
                                    'workshop'  => 'Workshop',
                                    'pelatihan' => 'Pelatihan',
                                    'lainnya'   => 'Lainnya',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $typeLabels[$event->type] ?? $event->type }}
                            </span>
                        </td>
                        <td>
                            <p class="text-sm font-medium text-slate-900">{{ $event->start_time->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $event->start_time->format('H:i') }}
                                @if($event->end_time) – {{ $event->end_time->format('H:i') }}@endif
                            </p>
                        </td>
                        <td class="text-sm text-slate-600">{{ Str::limit($event->location, 30) }}</td>
                        <td>
                            @if($event->start_time > now())
                                <x-ui.status-badge status="active">Mendatang</x-ui.status-badge>
                            @else
                                <x-ui.status-badge status="closed">Selesai</x-ui.status-badge>
                            @endif
                        </td>
                        <td>
                            <div class="ui-table-actions justify-end">
                                <a href="{{ route('admin.events.registrants', $event) }}" class="text-green-600 hover:text-green-800">Peserta</a>
                                <a href="{{ route('admin.events.edit', $event) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" onsubmit="return confirm('Hapus acara ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <x-ui.empty-state title="Belum ada acara" description="Buat acara karir pertama untuk siswa dan alumni." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $events->links() }}
        </div>
        @endif
    </x-ui.panel>
</x-app-layout>
