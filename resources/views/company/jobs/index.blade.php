@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lowongan Saya</h1>
            <p class="text-sm text-gray-600">Kelola semua lowongan yang Anda terbitkan.</p>
        </div>

        <form action="{{ route('company.jobs.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" placeholder="Cari lowongan..." value="{{ request('search') }}" class="w-full md:w-72 px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50" />
            <select name="status" class="px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
            </select>
            <button type="submit" class="px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Saring</button>
        </form>
        <div class="mt-3 md:mt-0">
            <a href="{{ route('company.jobs.create') }}" class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 shadow transition">Buat Lowongan</a>
        </div>
    </div>

    @if($jobs->isEmpty())
    <div class="bg-white rounded-3xl shadow p-8 text-center">
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Belum ada lowongan</h2>
        <p class="text-gray-600 mb-6">Mulai buat lowongan pertama Anda untuk menarik pelamar.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">Kembali ke Dasbor</a>
    </div>
    @else
    <div class="space-y-4">
    @foreach($jobs as $job)
    <x-card class="rounded-3xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ $job->title }}</h3>
                    <p class="text-gray-600">{{ $job->position }} &middot; {{ \App\Support\Label::jobStatus($job->status) }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-600">{{ $job->applications_count }} pelamar</span>
                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">Lihat</a>
                    <a href="{{ route('company.jobs.edit', $job) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">Ubah</a>
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <p>{{ \Illuminate\Support\Str::limit($job->description, 180) }}</p>
            </div>
        </x-card>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $jobs->links() }}
    </div>
    @endif
</div>
@endsection
