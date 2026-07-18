<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Peserta Acara" subtitle="{{ $event->title }}">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <!-- Info Acara -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card label="Total Pendaftar" :value="$registrations->total()" icon="users" color="slate" />
        <x-ui.stat-card label="Terdaftar" :value="$event->registrations()->where('status','registered')->count()" icon="check" color="green" />
        <x-ui.stat-card label="Dibatalkan" :value="$event->registrations()->where('status','cancelled')->count()" icon="x" color="red" />
        <x-ui.stat-card label="Tanggal Acara" :value="$event->start_time->format('d M Y')" subtitle="{{ $event->start_time->format('H:i') }} WIB" icon="calendar" color="purple" />
    </div>

    <!-- Tabel Peserta -->
    <x-ui.panel>
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between -mx-6 -mt-6">
            <h3 class="text-lg font-bold text-slate-900">Daftar Peserta</h3>
            <span class="text-sm text-slate-500">{{ $registrations->total() }} orang</span>
        </div>
        <div class="ui-table-wrap -mx-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($reg->user->avatar)
                                <img src="{{ asset('storage/' . $reg->user->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                @else
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-bold border border-slate-200">
                                    {{ substr($reg->user->name, 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $reg->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $reg->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleLabels = ['student'=>'Siswa','alumni'=>'Alumni','teacher'=>'Guru','company'=>'Perusahaan', 'admin' => 'Admin'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $roleLabels[$reg->user->role] ?? $reg->user->role }}
                            </span>
                        </td>
                        <td>
                            @if($reg->status === 'registered')
                                <x-ui.status-badge status="active">Terdaftar</x-ui.status-badge>
                            @elseif($reg->status === 'cancelled')
                                <x-ui.status-badge status="closed">Dibatalkan</x-ui.status-badge>
                            @else
                                <x-ui.status-badge status="verified">Hadir</x-ui.status-badge>
                            @endif
                        </td>
                        <td class="text-sm text-slate-500">{{ $reg->notes ?? '-' }}</td>
                        <td class="text-sm text-slate-500">{{ $reg->registered_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <x-ui.empty-state title="Belum ada peserta yang mendaftar" description="Belum ada yang mendaftar ke acara ini." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registrations->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">{{ $registrations->links() }}</div>
        @endif
    </x-ui.panel>
</x-app-layout>
