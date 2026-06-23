<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50">
    <!-- Admin Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
                        <p class="text-blue-100">Complete system overview and analytics</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="flex items-center gap-3">
                            <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg text-white">
                                <p class="text-sm">Today</p>
                                <p class="font-semibold">{{ now()->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Main Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Students Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Students</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+5.2%</span>
                        <span class="text-gray-600 ml-2">from last month</span>
                    </div>
                </div>

                <!-- Alumni Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Alumni</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_alumni'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+12.5%</span>
                        <span class="text-gray-600 ml-2">from last month</span>
                    </div>
                </div>

                <!-- Companies Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Companies</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_companies'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+3</span>
                        <span class="text-gray-600 ml-2">new this month</span>
                    </div>
                </div>

                <!-- Jobs Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Active Jobs</p>
                            <p class="text-4xl font-bold text-gray-900">{{ $stats['total_jobs'] }}</p>
                        </div>
                        <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 font-semibold">+8</span>
                        <span class="text-gray-600 ml-2">new postings</span>
                    </div>
                </div>
            </div>

            <!-- Application Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Applications</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Pending Review</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['interviews_scheduled'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Interviews</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-5 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['accepted_applications'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Accepted</p>
                </div>
            </div>

            <!-- Charts & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Application Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Application Trends</h3>
                    <div class="h-64 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                        <p class="text-gray-500">Chart akan ditampilkan di sini (Chart.js)</p>
                    </div>
                </div>

                <!-- Job Posting Chart -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Job Posting Trends</h3>
                    <div class="h-64 flex items-center justify-center bg-gradient-to-br from-green-50 to-teal-50 rounded-xl">
                        <p class="text-gray-500">Chart akan ditampilkan di sini (Chart.js)</p>
                    </div>
                </div>
            </div>

            <!-- Recent Applications & Top Companies -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Applications -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Recent Applications</h3>
                    <div class="space-y-4">
                        @foreach($recentApplications->take(5) as $app)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-600 font-bold text-lg">{{ substr($app->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $app->user->name }}</p>
                                <p class="text-sm text-gray-600 truncate">{{ $app->job->title }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                {{ ucfirst($app->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Companies -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Top Companies</h3>
                    <div class="space-y-4">
                        @foreach($topCompanies as $company)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ substr($company->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $company->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $company->job_count }} jobs posted</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
