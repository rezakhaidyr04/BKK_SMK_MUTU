<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Lowongan</h2>
                <p class="text-sm text-gray-500">Detail lengkap lowongan dan pelamar.</p>
            </div>
            <a href="{{ route('admin.jobs.edit', $job) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Ubah</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Lowongan</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Judul:</strong> {{ $job->title }}</p>
                            <p><strong>Posisi:</strong> {{ $job->position }}</p>
                            <p><strong>Perusahaan:</strong> {{ optional($job->company)->name ?? '-' }}</p>
                            <p><strong>Lokasi:</strong> {{ $job->location }}</p>
                            <p><strong>Tipe:</strong> {{ ucwords(str_replace('_', ' ', $job->job_type)) }}</p>
                            <p><strong>Status:</strong> {{ \App\Support\Label::jobStatus($job->status) }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Administratif</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Deadline:</strong> {{ optional($job->deadline)->format('d M Y') ?? '-' }}</p>
                            <p><strong>Gaji:</strong> {{ $job->salary_min ? 'Rp ' . number_format($job->salary_min, 0, ',', '.') : '-' }} - {{ $job->salary_max ? 'Rp ' . number_format($job->salary_max, 0, ',', '.') : '-' }}</p>
                            <p><strong>Diposting:</strong> {{ $job->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi</h3>
                <div class="prose prose-sm text-gray-700">{!! nl2br(e($job->description)) !!}</div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pelamar</h3>
                @if($job->applications->count() > 0)
                <div class="space-y-4">
                    @foreach($job->applications as $application)
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <p class="font-semibold text-gray-900">{{ optional($application->user)->name ?? 'Pengguna tidak tersedia' }}</p>
                        <p class="text-sm text-gray-600">{{ \App\Support\Label::applicationStatus($application->status) }} • {{ $application->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500">Belum ada pelamar untuk lowongan ini.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
