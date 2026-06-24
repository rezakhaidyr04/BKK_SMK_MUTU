<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Manajemen Berita</h1>
                        <p class="text-purple-100">Kelola artikel dan berita karir untuk platform.</p>
                    </div>
                    <a href="{{ route('admin.news.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tulis Berita
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Berita</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $news->total() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Dipublikasikan</p>
                            <p class="text-4xl font-bold text-green-600">{{ \App\Models\News::where('is_published', true)->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Draft</p>
                            <p class="text-4xl font-bold text-yellow-600">{{ \App\Models\News::where('is_published', false)->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">
                <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-48">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Judul</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                               placeholder="Cari berita...">
                    </div>
                    <div class="w-44">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                            Saring
                        </button>
                        <a href="{{ route('admin.news.index') }}"
                           class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Berita</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Berita</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penulis</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($news as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->thumbnail)
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt=""
                                             class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                        @else
                                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900 max-w-xs truncate">{{ $item->title }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit(strip_tags($item->content), 55) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->category)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">{{ $item->category }}</span>
                                    @else
                                    <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_published)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Dipublikasikan</span>
                                    @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->author->name ?? 'Admin' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.news.edit', $item) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg hover:bg-blue-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline"
                                              onsubmit="return confirm('Hapus berita ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-100 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada berita</p>
                                    <p class="text-gray-400 text-sm mt-1">Mulai tulis berita karir pertama untuk platform.</p>
                                    <a href="{{ route('admin.news.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                                        Tulis Berita
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($news->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $news->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
