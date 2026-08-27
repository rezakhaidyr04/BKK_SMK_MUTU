<x-app-layout :full-bleed="true">
    <x-slot name="header">
        <x-ui.page-header title="Daftar Lowongan" subtitle="Kelola lowongan kerja yang diposting oleh perusahaan.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.jobs.create') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Lowongan
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>
    <div class="page-shell">
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
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="text-blue-600 hover:text-blue-800">Ubah</a>
                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus lowongan ini?');">
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
