<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
    <!-- Hero Search Section -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 shadow-2xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-3">Find Your Dream Job</h1>
                    <p class="text-blue-100 text-lg">Discover {{ $jobs->total() }} opportunities waiting for you</p>
                </div>

                <!-- Advanced Search Form -->
                <form action="{{ route('jobs.index') }}" method="GET" class="max-w-5xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-2xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           placeholder="Job title, position, company..."
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <select name="location" class="w-full py-3 px-4 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Job Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
                                <select name="job_type" class="w-full py-3 px-4 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="">All Types</option>
                                    <option value="full_time" {{ request('job_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ request('job_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('jobs.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Clear Filters
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search Jobs
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Results Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Available Positions</h2>
                    <p class="text-gray-600 mt-1">Showing {{ $jobs->firstItem() }}-{{ $jobs->lastItem() }} of {{ $jobs->total() }} jobs</p>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700">Sort by:</label>
                    <select name="sort" onchange="window.location.href = updateQueryParam('sort', this.value)" 
                            class="py-2 px-4 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Salary: High to Low</option>
                        <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Salary: Low to High</option>
                        <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                    </select>
                </div>
            </div>

            <!-- Job Cards Grid -->
            @if($jobs->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @foreach($jobs as $job)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-blue-200 overflow-hidden group card">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <!-- Company Logo -->
                            <div class="flex-shrink-0">
                                @if($job->company->user->avatar ?? null)
                                <img src="{{ asset('storage/' . $job->company->user->avatar) }}" 
                                     alt="{{ $job->company->name }}" 
                                     class="w-16 h-16 rounded-xl object-cover border-2 border-white shadow-md">
                                @else
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ substr($job->company->name ?? 'C', 0, 1) }}
                                </div>
                                @endif
                            </div>

                            <!-- Job Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">
                                    <a href="{{ route('jobs.show', $job->id) }}">{{ $job->title }}</a>
                                </h3>
                                <p class="text-gray-600 font-medium mb-2">{{ $job->company->name ?? 'Company' }}</p>
                                
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <!-- Location -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $job->location }}
                                    </span>

                                    <!-- Job Type -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                    </span>

                                    <!-- Deadline -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $job->deadline->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Salary -->
                                @if($job->salary_min && $job->salary_max)
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                                    </span>
                                </div>
                                @endif

                                <!-- Description Preview -->
                                <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                    {{ Str::limit(strip_tags($job->description), 120) }}
                                </p>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('jobs.show', $job->id) }}" 
                                       class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition text-center">
                                        View Details
                                    </a>
                                    
                                    @auth
                                    <button onclick="toggleBookmark({{ $job->id }})" 
                                            class="p-2.5 border-2 border-gray-300 rounded-lg hover:border-red-500 hover:bg-red-50 transition-colors bookmark-btn-{{ $job->id }}">
                                        <svg class="w-5 h-5 text-gray-600 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Stats -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-500">
                                Posted {{ $job->created_at->diffForHumans() }}
                            </span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $job->applications_count ?? 0 }} applicants
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No jobs found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search criteria or filters</p>
                <a href="{{ route('jobs.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Clear All Filters
                </a>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, value);
            return url.toString();
        }

        function toggleBookmark(jobId) {
            fetch(`/jobs/${jobId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.querySelector(`.bookmark-btn-${jobId} svg`);
                if (data.bookmarked) {
                    btn.setAttribute('fill', 'currentColor');
                    btn.classList.add('text-red-500');
                } else {
                    btn.setAttribute('fill', 'none');
                    btn.classList.remove('text-red-500');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
