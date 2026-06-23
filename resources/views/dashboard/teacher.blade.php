@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Dashboard Guru</h1>
        <p class="text-sm text-gray-600">Ringkasan penempatan dan monitoring siswa</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Total Siswa</div>
            <div class="text-2xl font-semibold">{{ $stats['total_students'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Total Alumni</div>
            <div class="text-2xl font-semibold">{{ $stats['total_alumni'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Siswa Ditempatkan</div>
            <div class="text-2xl font-semibold">{{ $stats['placed_students'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Lowongan Aktif</div>
            <div class="text-2xl font-semibold">{{ $stats['active_jobs'] ?? 0 }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg p-4 shadow-soft-modern">
            <h2 class="font-semibold mb-3">Penempatan Terbaru</h2>
            @if($recentPlacements->isEmpty())
                <p class="text-gray-500">Belum ada penempatan terbaru.</p>
            @else
                <ul class="space-y-3">
                    @foreach($recentPlacements as $app)
                        <li class="flex items-start gap-3">
                            <div class="flex-1">
                                <div class="font-medium">{{ $app->user->name ?? 'N/A' }} — {{ $app->job->title ?? 'Lowongan' }}</div>
                                <div class="text-xs text-gray-500">{{ $app->job->company->name ?? 'Perusahaan' }} · {{ $app->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="text-sm text-green-600 font-semibold">Diterima</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="bg-white rounded-lg p-4 shadow-soft-modern">
            <h3 class="font-semibold mb-3">Status Penempatan</h3>
            @if($placementData->isEmpty())
                <p class="text-gray-500">Tidak ada data.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach($placementData as $p)
                        <li class="flex justify-between"><span class="capitalize">{{ $p->status }}</span><span>{{ $p->count }}</span></li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>
</div>
@endsection
