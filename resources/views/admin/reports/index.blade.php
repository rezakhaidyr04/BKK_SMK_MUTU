<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Admin</h2>
                <p class="text-sm text-gray-500">Ringkasan performa sistem dan ekspor data.</p>
            </div>
            <a href="{{ route('admin.reports.export') }}" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-700">Ekspor CSV</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Total Siswa</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['total_students'] }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Total Alumni</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['total_alumni'] }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Total Perusahaan</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['total_companies'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Total Lowongan</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['total_jobs'] }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Lowongan Aktif</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['active_jobs'] }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Total Aplikasi</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['total_applications'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Aplikasi Diajukan</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['submitted_applications'] }}</p>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <p class="text-sm font-semibold text-gray-500 uppercase">Aplikasi Diterima</p>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $summary['accepted_applications'] }}</p>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Terbaru</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach($recentUsers as $user)
                    <div class="rounded-2xl border border-gray-200 p-4">
                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }} • {{ \App\Support\Label::role($user->role) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Perusahaan Terbaru</h3>
                    <div class="space-y-3">
                        @foreach($recentCompanies as $company)
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="font-semibold text-gray-900">{{ $company->name }}</p>
                            <p class="text-sm text-gray-500">{{ $company->industry }} • {{ $company->created_at->format('d M Y') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Lowongan Terbaru</h3>
                    <div class="space-y-3">
                        @foreach($recentJobs as $job)
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                            <p class="text-sm text-gray-500">{{ optional($job->company)->name ?? '-' }} • {{ \App\Support\Label::jobStatus($job->status) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
