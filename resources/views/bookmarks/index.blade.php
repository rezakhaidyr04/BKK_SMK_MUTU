<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="Lowongan Tersimpan" subtitle="Lowongan yang Anda tandai untuk dilihat nanti." />

        <div class="page-container page-section">
            <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">Gunakan sebagai shortlist</p>
                        <p class="text-sm text-emerald-700 mt-1">Simpan lowongan yang menarik, lalu bandingkan sebelum mengirim lamaran.</p>
                    </div>
                    <x-ui.btn href="{{ route('jobs.index') }}" variant="secondary" size="sm">Cari Lowongan</x-ui.btn>
                </div>
            </div>

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
                    description="Belum ada shortlist. Simpan lowongan yang cocok agar mudah dibandingkan dan dibuka kembali nanti."
                >
                    <x-slot:action>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <x-ui.btn href="{{ route('jobs.index') }}">Jelajahi Lowongan</x-ui.btn>
                            <x-ui.btn href="{{ route('dashboard') }}" variant="secondary">Kembali ke Dasbor</x-ui.btn>
                        </div>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-ui.panel>
            @endif
        </div>
    </div>
</x-app-layout>
