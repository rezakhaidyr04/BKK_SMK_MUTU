<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Job Alert" subtitle="Dapat email mingguan berisi lowongan yang cocok dengan kriteria Anda." eyebrow="Dashboard › Job Alert">
            <x-slot:chips>
                <span class="page-banner__chip">{{ $alerts->count() }}/{{ \App\Models\JobAlert::MAX_PER_USER }} langganan</span>
                <span class="page-banner__chip">Email mingguan</span>
            </x-slot:chips>
        </x-ui.page-banner>

        <div class="page-container page-section grid gap-6 lg:grid-cols-5 max-w-5xl mx-auto">
            <div class="lg:col-span-2">
                <x-ui.form-errors />
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
                @endif
                <form action="{{ route('job-alerts.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    @csrf
                    <h2 class="font-bold text-gray-900 mb-4">Buat langganan baru</h2>
                    <div class="mb-4">
                        <label for="keyword" class="block text-sm font-semibold text-gray-900 mb-2">Kata kunci</label>
                        <input type="text" name="keyword" id="keyword" maxlength="100" value="{{ old('keyword') }}" placeholder="cth: operator, admin, programmer"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="job_type" class="block text-sm font-semibold text-gray-900 mb-2">Tipe pekerjaan</label>
                        <select name="job_type" id="job_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua tipe</option>
                            @foreach($jobTypes as $type)
                                <option value="{{ $type }}" @selected(old('job_type') === $type)>{{ \App\Support\Label::jobType($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5 grid gap-4">
                        <x-job-location-fields :province="old('province')" :city="old('city')" :district="old('district')"
                            provincePlaceholder="Semua provinsi" cityPlaceholder="Semua kab/kota" districtPlaceholder="Semua kecamatan" :showErrors="true" />
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition-colors">Buat Langganan</button>
                    <p class="mt-2 text-xs text-gray-400">Isi minimal satu kriteria agar email tidak berisi semua lowongan.</p>
                </form>
            </div>

            <div class="lg:col-span-3 space-y-3">
                @forelse($alerts as $alert)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl {{ $alert->is_active ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">{{ $alert->criteriaLabel() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $alert->is_active ? 'Aktif · email tiap Sabtu' : 'Dijeda' }}
                            @if($alert->last_sent_at) · terakhir {{ $alert->last_sent_at->diffForHumans() }} @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <form action="{{ route('job-alerts.toggle', $alert) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border {{ $alert->is_active ? 'border-amber-200 text-amber-600 hover:bg-amber-50' : 'border-green-200 text-green-600 hover:bg-green-50' }} transition">
                                {{ $alert->is_active ? 'Jeda' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('job-alerts.destroy', $alert) }}" method="POST" onsubmit="return confirm('Hapus langganan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50 transition">Hapus</button>
                        </form>
                    </div>
                </div>
                @empty
                <x-ui.panel>
                    <x-ui.empty-state icon="search" title="Belum ada langganan"
                        description="Buat langganan pertama — misalnya kata kunci sesuai keahlian dan kota Anda — agar lowongan cocok datang sendiri tiap minggu." />
                </x-ui.panel>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
