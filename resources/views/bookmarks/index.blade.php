<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Lowongan Tersimpan" subtitle="Lowongan yang Anda tandai untuk dilihat nanti." />

        <div class="page-container page-section">
            @if($bookmarks->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($bookmarks as $bookmark)
                <div class="ui-panel hover:shadow-md transition-shadow">
                    <div class="ui-panel-body">
                        <h3 class="text-lg font-bold text-slate-900">{{ $bookmark->job->title }}</h3>
                        <p class="text-slate-600 mt-1">{{ $bookmark->job->company->name ?? 'Perusahaan' }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <x-ui.btn href="{{ route('jobs.show', $bookmark->job->id) }}" size="sm">Lihat Lowongan</x-ui.btn>
                            <form action="{{ route('bookmarks.destroy', $bookmark->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-ui.btn variant="danger" type="submit" size="sm">Hapus</x-ui.btn>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $bookmarks->links() }}</div>
            @else
            <x-ui.panel>
                <x-ui.empty-state
                    title="Belum ada lowongan tersimpan"
                    description="Simpan lowongan menarik agar mudah ditemukan kembali."
                >
                    <x-slot:action>
                        <x-ui.btn href="{{ route('jobs.index') }}">Jelajahi Lowongan</x-ui.btn>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
