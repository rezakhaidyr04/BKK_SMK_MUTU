<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Detail Perusahaan" subtitle="Informasi lengkap perusahaan employer.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('admin.companies.edit', $company) }}" size="sm">Ubah</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.panel title="Profil Perusahaan" class="mb-6">
        <div class="grid gap-6 md:grid-cols-2 text-sm">
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Nama:</span> {{ $company->name }}</p>
                <p><span class="font-semibold text-slate-700">Industri:</span> {{ $company->industry ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Website:</span>
                    @if($company->website)
                    <a href="{{ $company->website }}" class="text-blue-600 hover:underline" target="_blank">{{ $company->website }}</a>
                    @else - @endif
                </p>
                <p><span class="font-semibold text-slate-700">Alamat:</span> {{ $company->address ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Verifikasi:</span>
                    <x-ui.status-badge :status="$company->verification_status ?? ($company->is_verified ? 'verified' : 'pending')" />
                </p>
            </div>
            <div class="space-y-3">
                <p><span class="font-semibold text-slate-700">Email:</span> {{ optional($company->user)->email ?? '-' }}</p>
                <p><span class="font-semibold text-slate-700">Terdaftar:</span> {{ $company->created_at->format('d M Y H:i') }}</p>
                <p><span class="font-semibold text-slate-700">Lowongan:</span> {{ $company->jobs->count() }}</p>
            </div>
        </div>
    </x-ui.panel>

    <x-ui.panel title="Lowongan Terbaru">
        <div class="space-y-4">
            @forelse($company->jobs as $job)
            <div class="rounded-xl border border-slate-200 p-4">
                <p class="font-semibold text-slate-900">{{ $job->title }}</p>
                <p class="text-sm text-slate-600">{{ \App\Support\Label::jobStatus($job->status) }} · {{ $job->location }}</p>
                <p class="text-sm text-slate-500 mt-2">{{ \Illuminate\Support\Str::limit($job->description, 120) }}</p>
            </div>
            @empty
            <x-ui.empty-state title="Belum ada lowongan" description="Perusahaan ini belum memposting lowongan." />
            @endforelse
        </div>
    </x-ui.panel>
</x-app-layout>
