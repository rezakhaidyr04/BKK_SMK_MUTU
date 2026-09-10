<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Daftar Lowongan" subtitle="Kelola lowongan kerja yang diposting oleh perusahaan." eyebrow="Admin › Lowongan">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Manajemen Lowongan
                </span>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $jobs->total() }} Lowongan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.jobs.create') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Lowongan
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">
            <!-- Filter -->
            <x-ui.card class="mb-6">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid gap-4 md:grid-cols-3 w-full">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Judul, posisi, lokasi"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="ui-select">
                            <option value="">Semua</option>
                            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Menunggu</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="closed"   {{ request('status') == 'closed'   ? 'selected' : '' }}>Ditutup</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <x-ui.btn type="submit">Saring</x-ui.btn>
                        <x-ui.btn variant="secondary" href="{{ route('admin.jobs.index') }}">Atur Ulang</x-ui.btn>
                    </div>
                </form>
            </x-ui.card>

            <x-ui.panel>
                <div class="ui-table-wrap -mx-6 -mt-6">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $job)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $job->title }}</td>
                                <td>{{ $job->company_name ?? '-' }}</td>
                                <td>
                                    <x-ui.status-badge :status="$job->status">
                                        {{ \App\Support\Label::jobStatus($job->status) }}
                                    </x-ui.status-badge>
                                </td>
                                <td>{{ optional($job->deadline)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <div class="ui-table-actions">
                                        <x-ui.btn href="{{ route('admin.jobs.show', $job) }}" variant="secondary" size="sm">Lihat</x-ui.btn>
                                        <x-ui.btn href="{{ route('admin.jobs.edit', $job) }}" variant="secondary" size="sm">Ubah</x-ui.btn>
                                        @if($job->status === 'pending')
                                        <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="inline" data-confirm="Setujui dan publikasikan lowongan ini?" data-confirm-title="Setujui" data-confirm-ok="Setujui">
                                            @csrf
                                            <x-ui.btn type="submit" variant="success" size="sm">Setujui</x-ui.btn>
                                        </form>
                                        <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="inline" data-confirm="Tolak lowongan ini?" data-confirm-title="Tolak" data-confirm-ok="Tolak" data-confirm-variant="danger">
                                            @csrf
                                            <x-ui.btn type="submit" variant="danger" size="sm">Tolak</x-ui.btn>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline" data-confirm="Hapus lowongan ini?" data-confirm-title="Hapus" data-confirm-ok="Hapus" data-confirm-variant="danger">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <x-ui.empty-state title="Tidak ada lowongan ditemukan" description="Coba ubah filter pencarian." />
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $jobs->links() }}
                </div>
            </x-ui.panel>
        </div>
    </div>
</x-app-layout>
