<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Manajemen Acara" subtitle="Kelola acara karir, job fair, dan seminar.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.events.create') }}" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Acara
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Acara</th>
                        <th>Tipe</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <p class="font-medium text-slate-900">{{ $event->title }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($event->description, 60) }}</p>
                        </td>
                        <td>
                            <span class="ui-badge ui-badge-blue capitalize">{{ str_replace('_', ' ', $event->type) }}</span>
                        </td>
                        <td class="text-sm text-slate-600">
                            {{ $event->start_time->format('d M Y H:i') }}
                            @if($event->end_time)
                            <br><span class="text-slate-400">s/d {{ $event->end_time->format('d M Y H:i') }}</span>
                            @endif
                        </td>
                        <td>{{ $event->location }}</td>
                        <td>
                            <div class="ui-table-actions">
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
                        <td colspan="5">
                            <x-ui.empty-state title="Belum ada acara" description="Buat acara karir pertama untuk siswa dan alumni." />
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
</x-app-layout>
