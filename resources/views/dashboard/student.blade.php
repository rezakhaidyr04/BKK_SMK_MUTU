<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
<!-- Hero Header with Glassmorphism -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">Selamat datang kembali, {{ Auth::user()->name }}!</h1>
                                <p class="text-blue-100 mt-1">Mari temukan pekerjaan impian Anda hari ini</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Completion Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 min-w-[280px]">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-white font-medium">Kelengkapan Profil</span>
                            <span class="text-white font-bold">{{ $stats['profile_completion'] }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2.5">
                            <div class="bg-green-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $stats['profile_completion'] }}%"></div>
                        </div>
                        @if($stats['profile_completion'] < 100)
                        <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-sm text-white hover:text-blue-100 underline">
                            Lengkapi profil Anda →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Lamaran Aktif</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['active_applications'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Wawancara</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['interview_count'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Diterima</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['accepted_count'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-yellow-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Disimpan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['bookmarked_jobs'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 5 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-red-200 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">Pesan</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['unread_messages'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            @if($stats['unread_messages'] > 0)
                            <div class="relative">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                            </div>
                            @else
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column (2/3) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Job Recommendations Section -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Job Recommendations</h3>
                                        <p class="text-sm text-gray-600">Matched to your skills and profile</p>
                                    </div>
                                </div>
                                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                    View All
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($recommendedJobs->count() > 0)
                                <div class="grid gap-4">
                                    @foreach($recommendedJobs->take(3) as $job)
                                    <div class="group relative bg-gradient-to-br from-white to-gray-50 rounded-xl p-5 border border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                @if($job->company->user->avatar ?? null)
                                                <img src="{{ asset('storage/' . $job->company->user->avatar) }}" alt="{{ $job->company->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-white shadow-md">
                                                @else
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                    {{ substr($job->company->name ?? 'C', 0, 1) }}
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-1">
                                                    {{ $job->title }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mb-2">{{ $job->company->name ?? 'Company Name' }}</p>
                                                
                                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $job->location }}
                                                    </span>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                                                    </span>
                                                    @if($job->salary_min && $job->salary_max)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                                                    </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                        View Details
                                                    </a>
                                                    <button class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Match Score Badge -->
                                        <div class="absolute top-3 right-3">
                                            @php $score = $job->match_score ?? 0; @endphp
                                            @if($score >= 75)
                                                @php $badgeBg = 'bg-green-500'; $text = 'text-white'; @endphp
                                            @elseif($score >= 50)
                                                @php $badgeBg = 'bg-yellow-400'; $text = 'text-gray-900'; @endphp
                                            @else
                                                @php $badgeBg = 'bg-gray-200'; $text = 'text-gray-700'; @endphp
                                            @endif
                                            <div class="px-3 py-1 {{ $badgeBg }} {{ $text }} text-xs font-bold rounded-full shadow-lg">
                                                {{ $score }}% Match
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No recommendations yet</h4>
                                    <p class="text-gray-600 mb-4">Complete your profile and add skills to get personalized job recommendations</p>
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Complete Profile
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- My Applications Section -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">My Applications</h3>
                                        <p class="text-sm text-gray-600">Track your application status</p>
                                    </div>
                                </div>
                                <a href="{{ route('applications.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700 flex items-center gap-1">
                                    View All
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($myApplications->count() > 0)
                                <div class="space-y-4">
                                    @foreach($myApplications as $application)
                                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-5 border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-300">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h4 class="text-base font-bold text-gray-900 mb-1">{{ $application->job->title }}</h4>
                                                <p class="text-sm text-gray-600">{{ $application->job->company->name ?? 'Company' }}</p>
                                            </div>
                                            @php
                                                $statusConfig = [
                                                    'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Submitted'],
                                                    'under_review' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Under Review'],
                                                    'interviewed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Interview'],
                                                    'accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Accepted'],
                                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Rejected'],
                                                ];
                                                $status = $statusConfig[$application->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Unknown'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</span>
                                            <a href="{{ route('applications.show', $application->id) }}" class="text-purple-600 hover:text-purple-700 font-medium">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">No applications yet</h4>
                                    <p class="text-gray-600 mb-4">Start applying to jobs that match your skills</p>
                                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                        Browse Jobs
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column (1/3) -->
                <div class="space-y-6">
                    <!-- Activity Timeline -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-green-50 to-teal-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                                    <p class="text-sm text-gray-600">Your latest actions</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if(count($activities) > 0)
                                <div class="relative space-y-4">
                                    @foreach($activities as $activity)
                                    <div class="flex gap-4 group">
                                        <div class="flex flex-col items-center">
                                            @php
                                                $colorClasses = [
                                                    'blue' => 'bg-blue-500',
                                                    'green' => 'bg-green-500',
                                                    'yellow' => 'bg-yellow-500',
                                                    'red' => 'bg-red-500',
                                                    'purple' => 'bg-purple-500',
                                                    'gray' => 'bg-gray-500',
                                                ];
                                                $bgColor = $colorClasses[$activity['color']] ?? 'bg-gray-500';
                                            @endphp
                                            <div class="w-8 h-8 rounded-full {{ $bgColor }} flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($activity['icon'] === 'briefcase')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                    @endif
                                                </svg>
                                            </div>
                                            @if(!$loop->last)
                                            <div class="w-0.5 h-full bg-gray-200 flex-1"></div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 pb-6">
                                            <p class="text-sm font-semibold text-gray-900">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $activity['timestamp']->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600">No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Upcoming Events</h3>
                                    <p class="text-sm text-gray-600">Career opportunities</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($upcomingEvents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($upcomingEvents as $event)
                                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200 hover:border-orange-300 hover:shadow-md transition-all duration-300">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-orange-600 rounded-lg flex flex-col items-center justify-center text-white">
                                                    <span class="text-xs font-semibold">{{ $event->start_time->format('M') }}</span>
                                                    <span class="text-lg font-bold leading-none">{{ $event->start_time->format('d') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $event->title }}</h4>
                                                <p class="text-xs text-gray-600 mb-2">{{ ucfirst(str_replace('_', ' ', $event->type)) }}</p>
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $event->start_time->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-orange-100 rounded-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600">No upcoming events</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Browse Jobs</span>
                                </a>
                                
                                <a href="{{ route('cv.builder') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Build CV</span>
                                </a>
                                
                                <a href="{{ route('events.index') }}" class="flex items-center gap-3 p-3 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 transition-all duration-300 text-white group">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">View Events</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
