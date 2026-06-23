@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pelamar</h1>
            <p class="text-sm text-gray-600">Lihat semua pelamar untuk lowongan perusahaan Anda.</p>
        </div>

        <form action="{{ route('company.applicants.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="status" class="px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Terkirim</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Ditinjau</option>
                <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Wawancara</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="px-4 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="lg:col-span-3 grid gap-4">
            @foreach($applications as $application)
            <x-card class="rounded-3xl">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $application->job->title }}</h3>
                        <p class="text-gray-600">Pelamar: {{ $application->user->name }}</p>
                        <p class="text-gray-500 text-sm mt-2">{{ \Illuminate\Support\Str::limit($application->cover_letter ?? 'Tidak ada surat lamaran', 150) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Tanggal: {{ $application->created_at->format('d M Y') }}</p>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 mt-2">{{ ucfirst($application->status) }}</span>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('applications.show', $application) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">Lihat Detail</a>

                    @if(in_array($application->status, ['submitted','under_review','interviewed']))
                    <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Terima</button>
                    </form>

                    <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                    </form>
                    @endif
                    @if($application->status === 'accepted')
                    <form action="{{ route('company.applicants.sendOffer', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Kirim Offer</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach

            @if($applications->isEmpty())
            <div class="bg-white rounded-3xl shadow p-8 text-center">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Belum ada pelamar</h2>
                <p class="text-gray-600">Coba terbitkan lowongan lebih banyak agar pelamar menemukan perusahaan Anda.</p>
            </div>
            @endif

            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        </div>

        <aside class="bg-white rounded-3xl shadow p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pelamar</h2>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span>Total</span>
                    <span>{{ $stats['total'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span>Terkirim</span>
                    <span>{{ $stats['submitted'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span>Ditinjau</span>
                    <span>{{ $stats['under_review'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span>Wawancara</span>
                    <span>{{ $stats['interviewed'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span>Diterima</span>
                    <span>{{ $stats['accepted'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Ditolak</span>
                    <span>{{ $stats['rejected'] ?? 0 }}</span>
                </div>
            </div>
        </aside>
    </div>
</div>
                </div>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('applications.show', $application) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">Lihat Detail</a>

                    @if(in_array($application->status, ['submitted','under_review','interviewed']))
                    <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Terima</button>
                    </form>

                    <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                    </form>
                    @endif

                    @if($application->status === 'accepted')
                    <form action="{{ route('company.applicants.sendOffer', $application) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">Kirim Offer</button>
                    </form>
                    @endif
                </div>
            </x-card>
