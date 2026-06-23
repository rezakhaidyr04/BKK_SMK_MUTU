<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Perusahaan</h2>
                <p class="text-sm text-gray-500">Informasi lengkap perusahaan employer.</p>
            </div>
            <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Profil Perusahaan</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Nama:</strong> {{ $company->name }}</p>
                            <p><strong>Industri:</strong> {{ $company->industry }}</p>
                            <p><strong>Website:</strong> <a href="{{ $company->website }}" class="text-blue-600 hover:underline">{{ $company->website }}</a></p>
                            <p><strong>Alamat:</strong> {{ $company->address }}</p>
                            <p><strong>Terverifikasi:</strong> {{ $company->is_verified ? 'Ya' : 'Tidak' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Kontak</h3>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <p><strong>Email:</strong> {{ optional($company->user)->email ?? '-' }}</p>
                            <p><strong>Terdaftar:</strong> {{ $company->created_at->format('d M Y H:i') }}</p>
                            <p><strong>Lowongan:</strong> {{ $company->jobs->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lowongan Terbaru</h3>
                <div class="space-y-4">
                    @forelse($company->jobs as $job)
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                        <p class="text-gray-600 text-sm">{{ ucfirst($job->status) }} • {{ $job->location }}</p>
                        <p class="text-gray-500 text-sm mt-2">{{ \Illuminate\Support\Str::limit($job->description, 120) }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada lowongan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
