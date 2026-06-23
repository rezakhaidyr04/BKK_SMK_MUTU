@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Dashboard Perusahaan</h1>
        <p class="text-sm text-gray-600">Ringkasan lowongan dan pelamar</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Lowongan Aktif</div>
            <div class="text-2xl font-semibold">{{ $stats['active_jobs'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Total Pelamar</div>
            <div class="text-2xl font-semibold">{{ $stats['total_applicants'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Pelamar Baru</div>
            <div class="text-2xl font-semibold">{{ $stats['new_applicants'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-soft-modern">
            <div class="text-sm text-gray-500">Interview Terjadwal</div>
            <div class="text-2xl font-semibold">{{ $stats['scheduled_interviews'] ?? 0 }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg p-4 shadow-soft-modern">
            <h2 class="font-semibold mb-3">Pelamar Terbaru</h2>
            @if($recentApplicants->isEmpty())
                <p class="text-gray-500">Belum ada pelamar.</p>
            @else
                <ul class="space-y-3">
                    @foreach($recentApplicants as $app)
                        <li class="flex items-start gap-3">
                            <div class="flex-1">
                                <div class="font-medium">{{ $app->user->name ?? 'N/A' }} — {{ $app->job->title ?? 'Lowongan' }}</div>
                                <div class="text-xs text-gray-500">{{ $app->created_at->format('d M Y') }} · Status: {{ ucfirst($app->status) }}</div>
                            </div>
                            <a href="{{ route('applications.show', $app) }}" class="text-sm text-blue-600">Lihat</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="bg-white rounded-lg p-4 shadow-soft-modern">
            <h3 class="font-semibold mb-3">Performa Lowongan</h3>
            @if($jobPerformance->isEmpty())
                <p class="text-gray-500">Tidak ada data performa.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach($jobPerformance as $job)
                        <li class="flex justify-between"><span>{{ $job->title }}</span><span class="text-gray-600">{{ $job->applications_count }}</span></li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>
</div>
@endsection
