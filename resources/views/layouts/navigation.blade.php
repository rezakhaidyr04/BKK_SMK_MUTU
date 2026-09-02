<!-- Custom Sidebar Styling -->
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<!-- Top Navigation Bar -->
<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm" role="navigation" aria-label="Navigasi utama">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Sidebar Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors" aria-label="Buka/tutup menu navigasi" :aria-expanded="sidebarOpen.toString()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    </button>

                    <!-- Logo -->
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center ml-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logos/mutu_logo.png') }}" alt="Logo BKK SMK MUTU" class="w-10 h-10 rounded-xl object-cover">
                            <span class="text-xl font-bold text-gray-900 hidden sm:block">BKK SMK MUTU</span>
                        </div>
                    </a>
                </div>

                <!-- Right Side Navigation -->
                <div class="flex items-center gap-3">
                    @auth
                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors relative" aria-label="Notifikasi" :aria-expanded="notifOpen.toString()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 ui-notification-badge animate-pulse"></span>
                            @endif
                        </button>

                        <!-- Notifications Dropdown -->
                        <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto">
                            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                                <p class="text-sm font-semibold text-gray-900">Notifikasi</p>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.markAllRead') }}" class="text-xs text-blue-600 hover:underline">Tandai sudah dibaca</a>
                                @endif
                            </div>

                            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 border-b border-gray-50 {{ $notification->unread() ? 'bg-blue-50/50' : '' }}">
                                    <p class="text-xs text-gray-800">{{ $notification->data['message'] ?? 'Ada pembaruan status lamaran Anda.' }}</p>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    Belum ada notifikasi
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Menu pengguna" :aria-expanded="userOpen.toString()">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm" aria-hidden="true">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        @endif
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userOpen" @click.away="userOpen = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ Auth::user()->email }}</p>
                            </div>

                            @php
                                $profileRoute = Auth::user()->role === 'company'
                                    ? route('company.profile.edit')
                                    : route('profile.edit');

                                $profileLabel = match (Auth::user()->role) {
                                    'company' => 'Profil Perusahaan',
                                    'admin' => 'Pengaturan Admin',
                                    default => 'Pengaturan Profil',
                                };
                            @endphp

                            <a href="{{ $profileRoute }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $profileLabel }}
                            </a>

                            @if(Auth::user()->role === 'company')
                                     <a href="{{ $profileRoute }}#verification" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m1-3h.01M12 20v-6m-6 6h12"/>
                                    </svg>
                                    Verifikasi Perusahaan
                                </a>
                            @else
                                <!-- Divider for non-company users -->
                                <div class="border-t border-gray-100 my-2"></div>

                                <!-- Leave Review Button -->
                                <a href="{{ route('reviews.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-amber-600 hover:bg-amber-50 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Bagikan Ulasan
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="ui-btn ui-btn-secondary ui-btn-sm">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="ui-btn ui-btn-primary ui-btn-sm">
                            Daftar
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @auth
    <!-- Desktop Sidebar -->
    <aside x-show="sidebarOpen" x-cloak x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="sidebar-container fixed left-0 top-16 w-64 bg-white border-r border-gray-200 shadow-sm z-40 hidden lg:block">
        <div class="sidebar-content sidebar-scroll">
            <x-ui.sidebar-menu />
        </div>
    </aside>

    <!-- Mobile Sidebar Drawer -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 lg:hidden" style="z-index: 9999;" @keydown.escape.window="sidebarOpen = false">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-900/60 transition-opacity" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <div x-show="sidebarOpen"
             role="dialog"
             aria-modal="true"
             aria-label="Menu navigasi"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="absolute left-0 top-0 w-72 h-full bg-white shadow-2xl rounded-r-2xl flex flex-col overflow-hidden border-r border-gray-100">

            <!-- Mobile Header -->
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-slate-50">
                <div class="flex items-center gap-3 overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0" aria-hidden="true">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="p-2 -mr-2 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition-colors flex-shrink-0" aria-label="Tutup menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="sidebar-scroll flex-1 overflow-y-auto p-4 pb-8">
                <x-ui.sidebar-menu mobile />
            </div>
        </div>
    </div>
    @endauth
