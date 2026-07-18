<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Lowongan Saya" subtitle="Kelola semua lowongan yang Anda terbitkan.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.create') }}" size="sm">
                    Buat Lowongan
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="ui-filter-bar">
        <form action="{{ route('company.jobs.index') }}" method="GET" class="flex flex-wrap gap-4 items-end w-full">
            <div class="ui-filter-field">
                <label class="ui-label">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Judul lowongan...">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('company.jobs.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    @if($jobs->isEmpty())
        <x-ui.empty-state
            title="Belum ada lowongan"
            description="Mulai buat lowongan pertama Anda untuk menarik pelamar."
            :action-url="route('company.jobs.create')"
            action-label="Buat Lowongan"
        />
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($jobs as $job)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-slate-900 leading-tight">
                            <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600">{{ $job->title }}</a>
                        </h3>
                        <x-ui.status-badge :status="$job->status" />
                    </div>
                    <p class="text-sm text-slate-600 mb-4">{{ $job->position }}</p>
                    
                    <p class="text-sm text-slate-500 mb-6 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit($job->description, 120) }}
                    </p>
                    
                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                        <span class="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-2 py-1 rounded-md font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $job->applications_count }} pelamar
                        </span>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                        <x-ui.btn variant="secondary" size="sm" href="{{ route('jobs.show', $job) }}" class="flex-1 justify-center">Lihat Detail</x-ui.btn>
                        <x-ui.btn size="sm" href="{{ route('company.jobs.edit', $job) }}" class="flex-1 justify-center">Ubah Lowongan</x-ui.btn>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @endif
</x-app-layout>
