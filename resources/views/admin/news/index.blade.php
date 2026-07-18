<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Manajemen Berita" subtitle="Kelola artikel dan berita karir untuk platform.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.news.create') }}" size="sm">
                    Tulis Berita
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-ui.stat-card label="Total Berita" :value="$news->total()" icon="document-text" color="blue" />
        <x-ui.stat-card label="Dipublikasikan" :value="\App\Models\News::where('is_published', true)->count()" icon="check" color="green" />
        <x-ui.stat-card label="Draft" :value="\App\Models\News::where('is_published', false)->count()" icon="document" color="yellow" />
    </div>

    <!-- Filter -->
    <div class="ui-filter-bar mb-6">
        <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-wrap gap-4 items-end w-full">
            <div class="ui-filter-field flex-1 min-w-[200px]">
                <label class="ui-label">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Cari berita...">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.news.index') }}">Reset</x-ui.btn>
            </div>
        </form>
    </div>

    <!-- Table -->
    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Berita</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Penulis</th>
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
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-slate-200">
                                @else
                                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900 max-w-xs truncate">{{ $item->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit(strip_tags($item->content), 55) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $item->category }}
                            </span>
                            @else
                            <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_published)
                                <x-ui.status-badge status="active">Dipublikasikan</x-ui.status-badge>
                            @else
                                <x-ui.status-badge status="draft">Draft</x-ui.status-badge>
                            @endif
                        </td>
                        <td class="text-sm text-slate-600">{{ $item->author->name ?? 'Admin' }}</td>
                        <td class="text-sm text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="ui-table-actions justify-end">
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
                        <td colspan="6">
                            <x-ui.empty-state title="Belum ada berita" description="Mulai tulis berita karir pertama untuk platform." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($news->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $news->links() }}
        </div>
        @endif
    </x-ui.panel>
</x-app-layout>
