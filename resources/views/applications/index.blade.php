<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Lamaran Saya</h1>
                <p class="text-gray-600">Lacak dan kelola lamaran pekerjaan Anda</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-gray-400">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-600">Total</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-400">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['submitted'] }}</p>
                    <p class="text-xs text-gray-600">Terkirim</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-400">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['under_review'] }}</p>
                    <p class="text-xs text-gray-600">Ditinjau</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-400">
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['interviewed'] }}</p>
                    <p class="text-xs text-gray-600">Wawancara</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-400">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
                    <p class="text-xs text-gray-600">Diterima</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-red-400">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    <p class="text-xs text-gray-600">Ditolak</p>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6">
                <form action="{{ route('applications.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                    <label class="text-sm font-medium text-gray-700">Filter berdasarkan Status:</label>
                    <select name="status" class="px-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" onchange="this.form.submit()">
                        <option value="">Semua Lamaran</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Terkirim</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Ditinjau</option>
                        <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Wawancara</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @if(request('status'))
                    <a href="{{ route('applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Hapus Filter</a>
                    @endif
                </form>
            </div>

            <!-- Applications List -->
            @if($applications->count() > 0)
            <div class="space-y-4">
                @foreach($applications as $application)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- Company Logo -->
                            <div class="flex-shrink-0">
                                @if($application->job->company->user->avatar ?? null)
                                <img src="{{ asset('storage/' . $application->job->company->user->avatar) }}" 
                                     alt="{{ $application->job->company->name }}" 
                                     class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-md">
                                @else
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ substr($application->job->company->name ?? 'C', 0, 1) }}
                                </div>
                                @endif
                            </div>

                            <!-- Application Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-1">
                                            {{ $application->job->title }}
                                        </h3>
                                        <p class="text-gray-600 font-medium mb-2">{{ $application->job->company->name ?? 'Company' }}</p>
                                    </div>

                                    @php
                                        $statusConfig = [
                                            'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Terkirim'],
                                            'under_review' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Ditinjau'],
                                            'interviewed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Wawancara'],
                                            'accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Diterima'],
                                            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                        ];
                                        $status = $statusConfig[$application->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Tidak Diketahui'];
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <span class="inline-flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $application->job->location }}
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    <span class="inline-flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Melamar {{ $application->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('applications.show', $application->id) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                        Lihat Detail
                                    </a>
                                    
                                    <a href="{{ route('jobs.show', $application->job->id) }}" 
                                       class="px-4 py-2 border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:border-gray-400 transition-colors">
                                        Lihat Lowongan
                                    </a>

                                    @if(!in_array($application->status, ['accepted', 'rejected']))
                                    <form action="{{ route('applications.destroy', $application->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menarik lamaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-50 transition-colors">
                                            Tarik Lamaran
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada lamaran</h3>
                <p class="text-gray-600 mb-6">Mulai melamar pekerjaan yang sesuai dengan keahlian dan minat Anda</p>
                <a href="{{ route('jobs.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Cari Lowongan
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
