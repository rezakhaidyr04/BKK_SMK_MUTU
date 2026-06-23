<!-- Desktop sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col" x-show="true">
    <div class="glass-panel flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold text-primary-600">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                BKK MUTU
            </a>
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                                <svg class="h-6 w-6 shrink-0 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Dashboard
                            </a>
                        </li>

                        <!-- Cari Lowongan -->
                        <li>
                            <a href="{{ route('jobs.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('jobs.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600 transition" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Cari Lowongan
                            </a>
                        </li>

                        <!-- CV Builder -->
                        <li>
                            <a href="{{ route('cv.builder') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('cv.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600 transition" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                CV Builder (ATS)
                            </a>
                        </li>

                        <!-- Lamaran Saya -->
                        <li>
                            <a href="{{ route('applications.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('applications.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600 transition" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                Lamaran Saya
                            </a>
                        </li>
                        
                        <!-- Event Karir -->
                        <li>
                            <a href="{{ route('events.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('events.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600 transition" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                Event Karir
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Admin / Company Menus -->
                @if(Auth::user()->hasAnyRole(['admin', 'company']))
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400">Pengelolaan</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.jobs.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('admin.jobs.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                            @else
                            <a href="{{ route('company.jobs.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('company.jobs.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                            @endif
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                                </svg>
                                Kelola Lowongan
                            </a>
                        </li>
                        <li>
                            @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.users.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('admin.users.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                            @else
                            <a href="{{ route('company.applicants.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('company.applicants.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                            @endif
                                <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Pelamar & Kandidat
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                
                <li class="mt-auto">
                    <a href="{{ route('profile.edit') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('profile.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50 hover:text-primary-600' }} transition">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Add padding to main content when sidebar is visible on desktop -->
<div class="hidden lg:block lg:w-72 flex-shrink-0"></div>
