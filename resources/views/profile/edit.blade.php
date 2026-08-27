<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero title="{{ __('Pengaturan Akun') }}" subtitle="Kelola profil, keamanan, dan dokumen akun Anda." />

    <div class="py-6" x-data="{ currentTab: 'profile' }">
        <div class="page-container space-y-8">
            
            {{-- Header card dengan stats & Glassmorphism --}}
            <div class="relative overflow-hidden p-6 sm:p-8 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] sm:rounded-3xl border border-slate-100/80">
                {{-- Decorative background gradient glow --}}
                <div class="absolute -right-10 -top-10 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -bottom-10 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            @php
                                $avatarUrl = $user->avatar ? asset('storage/' . ltrim($user->avatar, '/')) : null;
                            @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                                     class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-md ring-1 ring-slate-100">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-600 to-blue-600 flex items-center justify-center text-white text-3xl font-extrabold shadow-md">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full shadow" title="Online"></div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider">
                                    {{ $user->role }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- Mini Stats --}}
                    <div class="flex items-center gap-4 bg-slate-50/80 backdrop-blur border border-slate-100 p-3 rounded-2xl">
                        <div class="px-5 py-2 text-center">
                            <span class="block text-xl font-extrabold text-slate-950">{{ $user->applications()->count() }}</span>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lamaran</span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="px-5 py-2 text-center">
                            <span class="block text-xl font-extrabold text-slate-950">{{ $user->bookmarks()->count() }}</span>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disimpan</span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="px-5 py-2 text-center">
                            <span class="block text-xl font-extrabold text-slate-950">{{ $user->certificates()->count() }}</span>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sertifikat</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Navigasi Premium --}}
            <div class="border-b border-slate-200/80">
                <nav class="-mb-px flex gap-6 overflow-x-auto" aria-label="Tabs">
                    <button @click="currentTab = 'profile'"
                            :class="currentTab === 'profile' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 font-semibold'"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Diri
                    </button>
                    <button @click="currentTab = 'password'"
                            :class="currentTab === 'password' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 font-semibold'"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Keamanan Sandi
                    </button>
                    @if(Auth::user()->isUmum())
                    <button @click="currentTab = 'documents'"
                            :class="currentTab === 'documents' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 font-semibold'"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Berkas Pendukung
                    </button>
                    @endif
                    @if(!in_array(Auth::user()->role, ['admin']))
                    <button @click="currentTab = 'danger'"
                            :class="currentTab === 'danger' ? 'border-red-500 text-red-600 font-bold' : 'border-transparent text-slate-500 hover:text-red-500 hover:border-red-300 font-semibold'"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 text-sm transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Zona Bahaya
                    </button>
                    @endif
                </nav>
            </div>

            {{-- Konten Tab --}}
            <div class="relative">
                
                {{-- Tab 1: Informasi Diri --}}
                <div x-show="currentTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 sm:p-8 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] sm:rounded-3xl border border-slate-100/80">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Tab 2: Keamanan Sandi --}}
                <div x-show="currentTab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 sm:p-8 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] sm:rounded-3xl border border-slate-100/80" style="display: none;">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Tab 3: Berkas Pendukung --}}
                @if(Auth::user()->isUmum())
                <div x-show="currentTab === 'documents'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 sm:p-8 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] sm:rounded-3xl border border-slate-100/80" style="display: none;">
                    @include('profile.partials.manage-documents-form')
                </div>
                @endif

                {{-- Tab 4: Zona Bahaya --}}
                @if(!in_array(Auth::user()->role, ['admin']))
                <div x-show="currentTab === 'danger'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 sm:p-8 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)] sm:rounded-3xl border border-red-100/30" style="display: none;">
                    @include('profile.partials.delete-user-form')
                </div>
                @endif

            </div>
        </div>
    </div>
    </div>

    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-view-box,
        .cropper-face {
          border-radius: 50%;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    @endpush
</x-app-layout>
