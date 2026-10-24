<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Manajemen Berita" subtitle="Kelola berita karir, tips dunia kerja, dan informasi industri untuk pencari kerja." eyebrow="Admin › Berita">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $news->total() }} Berita · Kelola Artikel Karir
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.news.create') }}" size="sm">
                    Buat Berita
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card label="Total Berita" :value="$news->total()" icon="newspaper" color="blue" />
        <x-ui.stat-card label="Diterbitkan" :value="\App\Models\News::where('is_published', true)->count()" icon="check" color="green" />
        <x-ui.stat-card label="Draft" :value="\App\Models\News::where('is_published', false)->count()" icon="clock" color="yellow" />
        <x-ui.stat-card label="Kategori" value="3" icon="layers" color="slate" />
    </div>

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('admin.news.index') }}" class="grid gap-4 md:grid-cols-4 w-full">
            <div class="ui-filter-field md:col-span-2">
                <label class="ui-label">Cari Berita</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Judul atau isi berita...">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Kategori</label>
                <select name="category" class="ui-select">
                    <option value="">Semua Kategori</option>
                    <option value="career_tips"  {{ request('category') === 'career_tips'  ? 'selected' : '' }}>Tips Karir</option>
                    <option value="work_world"   {{ request('category') === 'work_world'   ? 'selected' : '' }}>Dunia Kerja</option>
                    <option value="industry_info" {{ request('category') === 'industry_info' ? 'selected' : '' }}>Informasi Industri</option>
                </select>
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="is_published" class="ui-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Diterbitkan</option>
                    <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
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
                        <th>Berita</th>
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
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-slate-200">
                                @else
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($item->excerpt, 55) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                {{ \App\Support\Label::newsCategory($item->category) }}
                            </span>
                        </td>
                        <td>
                            @if($item->is_published)
                                <x-ui.status-badge status="active">Diterbitkan</x-ui.status-badge>
                            @else
                                <x-ui.status-badge status="closed">Draft</x-ui.status-badge>
                            @endif
                        </td>
                        <td>
                            <p class="text-sm font-medium text-slate-900">{{ optional($item->published_at)->format('d M Y') ?? $item->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ optional($item->published_at)->format('H:i') ?? $item->created_at->format('H:i') }}</p>
                        </td>
                        <td>
                            <div class="ui-table-actions justify-end">
                                <x-ui.btn href="{{ route('admin.news.edit', $item) }}" variant="secondary" size="sm">Edit</x-ui.btn>
                                <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline" data-confirm="Hapus berita ini?" data-confirm-title="Hapus" data-confirm-ok="Hapus" data-confirm-variant="danger">
                                    @csrf @method('DELETE')
                                    <x-ui.btn type="submit" variant="danger" size="sm">Hapus</x-ui.btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <x-ui.empty-state title="Belum ada berita" description="Buat artikel berita pertama untuk pencari kerja dan komunitas sekolah." />
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
        </div>
    </div>
</x-app-layout>
