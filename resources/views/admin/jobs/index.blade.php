<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Daftar Lowongan</h1>
                    <p class="text-purple-100">Kelola lowongan kerja yang diposting oleh perusahaan.</p>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <!-- Filter -->
            <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
                <form method="GET" action="{{ route('admin.jobs.index') }}" class="grid gap-4 md:grid-cols-4 w-full">
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
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Perusahaan</label>
                        <select name="company_id" class="ui-select">
                            <option value="">Semua</option>
                            @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <x-ui.btn type="submit">Saring</x-ui.btn>
                        <x-ui.btn variant="secondary" href="{{ route('admin.jobs.index') }}">Atur Ulang</x-ui.btn>
                    </div>
                </form>
            </div>

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
                                <td>{{ optional($job->company)->name ?? '-' }}</td>
                                <td>
                                    <x-ui.status-badge :status="$job->status">
                                        {{ \App\Support\Label::jobStatus($job->status) }}
                                    </x-ui.status-badge>
                                </td>
                                <td>{{ optional($job->deadline)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <div class="ui-table-actions">
                                        <a href="{{ route('admin.jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="text-indigo-600 hover:text-indigo-800">Ubah</a>
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
