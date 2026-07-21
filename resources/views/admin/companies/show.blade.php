<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Detail Perusahaan</h1>
                        <p class="text-purple-100">{{ $company->name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.companies.edit', $company) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                            Edit
                        </a>
                        <a href="{{ route('admin.companies.index') }}"
                           class="inline-flex items-center px-5 py-2.5 border border-white/30 text-white text-sm font-semibold rounded-xl hover:bg-white/10 transition">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Perusahaan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 text-center border-b border-gray-100 bg-gradient-to-br from-indigo-50 to-purple-50">
                            @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}"
                                 class="w-20 h-20 mx-auto rounded-2xl object-cover border-2 border-indigo-200 mb-3">
                            @else
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold mb-3">
                                {{ substr($company->name, 0, 1) }}
                            </div>
                            @endif
                            <h3 class="font-bold text-gray-900 text-lg">{{ $company->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $company->industry ?? '-' }}</p>
                            <div class="mt-2">
                                <x-ui.status-badge :status="$company->verification_status ?? ($company->is_verified ? 'verified' : 'pending')" />
                            </div>
                        </div>
                        <div class="p-5 space-y-3 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-700">{{ optional($company->user)->email ?? '-' }}</span>
                            </div>
                            @if($company->website)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $company->website }}</a>
                            </div>
                            @endif
                            @if($company->address)
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-gray-700">{{ $company->address }}</span>
                            </div>
                            @endif
                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-gray-500">Terdaftar</span>
                                <span class="font-medium text-gray-900">{{ $company->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total Lowongan</span>
                                <span class="font-bold text-indigo-600">{{ $company->jobs->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi + Lowongan -->
                <div class="lg:col-span-2 space-y-6">
                    @if($company->description)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Deskripsi Perusahaan</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $company->description }}</p>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900">Lowongan Terbaru</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @forelse($company->jobs as $job)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ \App\Support\Label::jobStatus($job->status) }} · {{ $job->location }}</p>
                                    </div>
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-xs text-indigo-600 font-semibold hover:underline flex-shrink-0">Detail</a>
                                </div>
                                @if($job->description)
                                <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($job->description, 100) }}</p>
                                @endif
                            </div>
                            @empty
                            <div class="p-10 text-center">
                                <p class="text-gray-400 text-sm">Belum ada lowongan.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
