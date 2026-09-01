{{-- Menu sidebar tunggal: dipakai oleh sidebar desktop dan drawer mobile --}}
@props([
    'mobile' => false,
])

@php
    $role = Auth::user()->role;
    $isCompanyVerified = Auth::user()->company?->is_verified;
@endphp

@if($role !== 'company')
<a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3">
    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    <span class="font-medium">Dasbor</span>
</a>
@endif

@if($role === 'umum')
<div class="{{ $mobile ? '' : 'mt-6' }}">
    <h3 class="nav-section-title">Pencarian Kerja</h3>

    <a href="{{ route('jobs.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Cari Lowongan</span>
    </a>

    <a href="{{ route('applications.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="font-medium">Lamaran Saya</span>
    </a>

    <a href="{{ route('bookmarks.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
        </svg>
        <span class="font-medium">Lowongan Tersimpan</span>
    </a>
</div>

<div class="mt-6">
    <h3 class="nav-section-title">Alat Karir</h3>

    <a href="{{ route('cv.builder') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('cv.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="font-medium">Pembuat CV</span>
        <span class="nav-badge green ml-auto">ATS</span>
    </a>

    <a href="{{ route('certificates.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('certificates.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
        <span class="font-medium">Sertifikat</span>
    </a>
</div>

<div class="mt-6">
    <h3 class="nav-section-title">Komunitas</h3>

    <a href="{{ route('events.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('events.index') || request()->routeIs('events.show') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Acara</span>
    </a>

    <a href="{{ route('events.my') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('events.my') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span class="font-medium">Acara Saya</span>
        @php $myEventCount = \App\Models\EventRegistration::where('user_id', Auth::id())->where('status','registered')->count(); @endphp
        @if($myEventCount > 0)
        <span class="ml-auto nav-badge green">{{ $myEventCount }}</span>
        @endif
    </a>

    <a href="{{ route('news.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <span class="font-medium">Berita Karir</span>
    </a>

    <a href="{{ route('messages.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <span class="font-medium">Pesan</span>
    </a>
</div>
@endif

@if($role === 'admin')
<div class="{{ $mobile ? '' : 'mt-6' }}">
    <h3 class="nav-section-title">Administrasi</h3>

    <a href="{{ route('admin.users.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span class="font-medium">Pengguna</span>
    </a>

    <a href="{{ route('admin.users.create') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        <span class="font-medium">Tambah Pengguna</span>
    </a>

    <a href="{{ route('admin.companies.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v12a2 2 0 002 2h10a2 2 0 002-2V7M5 7l7 5 7-5"/>
        </svg>
        <span class="font-medium">Perusahaan</span>
    </a>

    <a href="{{ route('admin.jobs.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Lowongan Kerja</span>
    </a>

    <a href="{{ route('admin.reports.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span class="font-medium">Laporan</span>
    </a>

    <a href="{{ route('admin.news.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <span class="font-medium">Berita</span>
    </a>

    <a href="{{ route('admin.events.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Acara</span>
    </a>
</div>
@endif

@if($role === 'company')
<div class="{{ $mobile ? 'company-nav-panel' : 'mt-6 company-nav-panel' }}">
    <h3 class="nav-section-title">Perusahaan</h3>
    <p class="mb-3 px-2 text-xs text-amber-700">Pusat rekrutmen untuk kelola lowongan dan pelamar</p>

    <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v10a1 1 0 01-1 1h-5a1 1 0 01-1-1v-5H10v5a1 1 0 01-1 1H4a1 1 0 01-1-1V10z"/>
        </svg>
        <span class="font-medium">Dashboard Perusahaan</span>
        <span class="nav-badge orange ml-auto">Utama</span>
    </a>

    <a href="{{ route('company.jobs.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('company.jobs.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <span class="font-medium">Lowongan Saya</span>
        <span class="nav-badge blue ml-auto">Daftar</span>
    </a>

    @if($isCompanyVerified)
        <a href="{{ route('company.jobs.create') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('company.jobs.create') ? 'active' : '' }} flex items-center gap-3">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="font-medium">Publish Lowongan</span>
            <span class="nav-badge orange ml-auto">Posting</span>
        </a>
    @else
        <div class="nav-link flex items-center gap-3 opacity-50 cursor-not-allowed" title="Menunggu verifikasi perusahaan">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="font-medium">Publish Lowongan</span>
            <span class="nav-badge gray ml-auto">Menunggu</span>
        </div>
    @endif

    <a href="{{ route('company.applicants.index') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('company.applicants.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="font-medium">Pelamar</span>
        <span class="nav-badge green ml-auto">Baru</span>
    </a>

    <a href="{{ route('company.profile.edit') }}" @click="sidebarOpen = false" class="nav-link {{ request()->routeIs('company.profile.*') ? 'active' : '' }} flex items-center gap-3">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span class="font-medium">Profil Perusahaan</span>
        <span class="nav-badge gray ml-auto">Edit</span>
    </a>
</div>
@endif

<div class="help-card mt-6">
    <h4 class="text-sm font-semibold text-gray-900 mb-1">Butuh Bantuan?</h4>
    <p class="text-xs text-gray-600 mb-3">Hubungi tim support kami</p>
    <a href="mailto:bkk@smkmutu.sch.id" class="inline-block text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
        bkk@smkmutu.sch.id →
    </a>
</div>
