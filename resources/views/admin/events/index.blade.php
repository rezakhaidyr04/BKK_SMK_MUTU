<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Manajemen Acara" subtitle="Kelola acara karier, job fair, dan seminar untuk pencari kerja dan komunitas sekolah." eyebrow="Admin › Acara">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $events->total() }} Acara · Kelola Kegiatan
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.events.create') }}" size="sm">
                    Buat Acara
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card label="Total Acara" :value="$events->total()" icon="calendar" color="blue" />
        <x-ui.stat-card label="Acara Mendatang" :value="\App\Models\Event::where('start_time', '>=', now())->count()" icon="clock" color="green" />
        <x-ui.stat-card label="Berbayar" :value="\App\Models\Event::where('is_paid', true)->count()" icon="check" color="yellow" />
        <x-ui.stat-card label="Gratis" :value="\App\Models\Event::where('is_paid', false)->orWhereNull('is_paid')->count()" icon="check" color="slate" />
    </div>

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('admin.events.index') }}" class="grid gap-4 md:grid-cols-4 w-full">
            <div class="ui-filter-field md:col-span-2">
                <label class="ui-label">Cari Acara</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Judul atau lokasi acara...">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Tipe</label>
                <select name="type" class="ui-select">
                    <option value="">Semua Tipe</option>
                    <option value="job_fair"  {{ request('type') === 'job_fair'  ? 'selected' : '' }}>Job Fair</option>
                    <option value="seminar"   {{ request('type') === 'seminar'   ? 'selected' : '' }}>Seminar</option>
                    <option value="workshop"  {{ request('type') === 'workshop'  ? 'selected' : '' }}>Workshop</option>
                    <option value="pelatihan" {{ request('type') === 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                    <option value="lainnya"   {{ request('type') === 'lainnya'   ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Acara</th>
                        <th>Tipe</th>
                        <th>Harga</th>
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
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            @if($event->is_paid)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                                @if($event->quota)<span class="block text-xs text-slate-400 mt-1">{{ $event->quota }} kuota</span>@endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Gratis</span>
                            @endif
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
                                <x-ui.btn href="{{ route('admin.events.registrants', $event) }}" variant="secondary" size="sm">Peserta</x-ui.btn>
                                <x-ui.btn href="{{ route('admin.events.edit', $event) }}" variant="secondary" size="sm">Edit</x-ui.btn>
                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" data-confirm="Hapus acara ini?" data-confirm-title="Hapus" data-confirm-ok="Hapus" data-confirm-variant="danger">
                                    @csrf @method('DELETE')
                                    <x-ui.btn type="submit" variant="danger" size="sm">Hapus</x-ui.btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-ui.empty-state title="Belum ada acara" description="Buat acara karier pertama untuk pencari kerja dan komunitas sekolah." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $events->links() }}
        </div>
    </x-ui.panel>
        </div>
    </div>
</x-app-layout>
