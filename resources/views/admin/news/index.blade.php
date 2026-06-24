<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Manajemen Berita" subtitle="Kelola artikel dan berita karir platform.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.news.create') }}" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tulis Berita
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-wrap gap-4 items-end w-full">
            <div class="ui-filter-field">
                <label class="ui-label">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Cari...">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select">
                    <option value="">Semua</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublish</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($item->thumbnail)
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-slate-900 max-w-xs truncate">{{ $item->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $item->author->name ?? 'Admin' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->category ?? '-' }}</td>
                        <td>
                            <x-ui.status-badge :status="$item->is_published ? 'published' : 'draft'" />
                        </td>
                        <td class="text-sm text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="ui-table-actions">
                                <a href="{{ route('admin.news.edit', $item) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <x-ui.empty-state title="Belum ada berita" description="Mulai tulis berita karir pertama untuk platform." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $news->links() }}
        </div>
    </x-ui.panel>
</x-app-layout>
