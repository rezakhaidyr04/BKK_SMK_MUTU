@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col gap-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900">Pelamar</h1>
            <p class="mt-2 text-sm text-gray-600">Lihat pelamar yang sudah mengajukan lamaran untuk lowongan perusahaan Anda.</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            @if($applications->isEmpty())
                <div class="text-center py-16">
                    <p class="text-lg font-semibold text-gray-900">Belum ada pelamar</p>
                    <p class="mt-2 text-sm text-gray-600">Pelamar akan muncul setelah ada lowongan aktif dan kandidat mengajukan lamaran.</p>
                    <a href="{{ route('company.jobs.index') }}" class="mt-6 inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Kelola Lowongan</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($applications as $application)
                        <div class="rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $application->user->name }}</h2>
                                    <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
                                </div>
                                <div class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-700">{{ ucfirst($application->status) }}</div>
                            </div>

                            <div class="mt-4 grid gap-4 md:grid-cols-3">
                                <div>
                                    <p class="text-sm text-gray-500">Lowongan</p>
                                    <p class="font-medium text-gray-900">{{ optional($application->job)->title ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Posisi</p>
                                    <p class="font-medium text-gray-900">{{ optional($application->job)->position ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Melamar</p>
                                    <p class="font-medium text-gray-900">{{ $application->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm text-gray-600">{{ Str::limit($application->cover_letter ?? '-', 140) }}</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="text-xs text-slate-500">CV/Terlampir: {{ $application->attachment_name ?? 'Tidak ada' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
