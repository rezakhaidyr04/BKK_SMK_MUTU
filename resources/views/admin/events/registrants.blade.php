<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Peserta Acara" subtitle="{{ $event->title }}" eyebrow="Admin › Acara">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $registrations->total() }} Peserta · {{ Str::limit($event->title, 24) }}
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.events.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <!-- Info Acara -->
    <div class="mb-4 flex flex-wrap gap-2">
        @if($event->is_paid)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-xs font-bold">Berbayar · Rp {{ number_format($event->price,0,',','.') }}</span>
            @if($event->quota)<span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">Kuota: {{ $event->registrations()->where('status','registered')->count() }}/{{ $event->quota }}</span>@endif
            @if($event->payment_instructions)<span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs">{{ Str::limit($event->payment_instructions, 60) }}</span>@endif
        @else
            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-bold">Gratis</span>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <x-ui.stat-card label="Total Pendaftar" :value="$registrations->total()" icon="users" color="slate" />
        <x-ui.stat-card label="Terdaftar" :value="$event->registrations()->where('status','registered')->count()" icon="check" color="green" />
        <x-ui.stat-card label="Menunggu Bayar" :value="$event->registrations()->where('payment_status','pending')->count()" icon="clock" color="yellow" />
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
                        <th>Pembayaran</th>
                        <th>Catatan</th>
                        <th>Waktu Daftar</th>
                        @if($event->is_paid)<th class="text-right">Aksi</th>@endif
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
                                $roleLabels = ['umum'=>'Pengguna Umum','company'=>'Perusahaan', 'admin' => 'Admin'];
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
                        <td>
                            @if(!$event->is_paid)
                                <span class="text-xs text-slate-400">— Gratis</span>
                            @elseif($reg->payment_status === 'verified')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Terverifikasi</span>
                                @if($reg->paid_at)<span class="block text-xs text-slate-400">{{ $reg->paid_at->format('d M Y') }}</span>@endif
                            @elseif($reg->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Menunggu</span>
                                @if($reg->payment_proof)
                                    <a href="{{ asset('storage/' . $reg->payment_proof) }}" target="_blank" class="block text-xs text-blue-600 underline mt-1">Lihat bukti</a>
                                @endif
                            @elseif($reg->payment_status === 'rejected')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Ditolak</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs">Belum bayar</span>
                            @endif
                        </td>
                        <td class="text-sm text-slate-500">{{ $reg->notes ?? '-' }}</td>
                        <td class="text-sm text-slate-500">{{ $reg->registered_at->format('d M Y, H:i') }}</td>
                        @if($event->is_paid)
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if($reg->payment_status === 'pending' && $reg->payment_proof)
                                    <form method="POST" action="{{ route('admin.events.verify-payment', [$event, $reg]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700">Verifikasi</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.events.reject-payment', [$event, $reg]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 border border-red-200 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-50">Tolak</button>
                                    </form>
                                @elseif($reg->payment_status === 'verified')
                                    <span class="text-xs text-slate-400">✓</span>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $event->is_paid ? 7 : 6 }}">
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
        </div>
    </div>
</x-app-layout>
