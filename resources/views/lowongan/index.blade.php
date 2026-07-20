{{-- resources/views/lowongan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Daftar Lowongan Pekerjaan')

@push('styles')
<style>
    /* Spesifik CSS untuk halaman ini */
    .job-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: var(--space-xl);
        margin-top: var(--space-xl);
    }
    
    .job-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        border-top: 4px solid var(--clr-primary-light);
    }
    
    .job-header {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        margin-bottom: var(--space-md);
    }
    
    .company-logo {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        box-shadow: var(--shadow-sm);
        border: 2px solid var(--clr-surface-solid);
    }
    
    .job-meta {
        font-size: 0.9rem;
        color: var(--clr-text-muted);
        margin-bottom: var(--space-md);
        display: flex;
        flex-direction: column;
        gap: var(--space-xs);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }
    
    .job-footer {
        margin-top: auto;
        padding-top: var(--space-md);
        border-top: 1px dashed var(--clr-border);
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: var(--clr-primary-alpha);
        color: var(--clr-primary-dark);
        margin-bottom: var(--space-sm);
    }

    .search-container {
        background: var(--clr-surface);
        padding: var(--space-md);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        margin-bottom: var(--space-xl);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: var(--space-md);
        border: 1px solid var(--clr-border);
    }
    
    .page-title {
        font-size: 2rem;
        background: linear-gradient(135deg, var(--clr-primary-dark), var(--clr-primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
    <div class="search-container">
        <h2 class="page-title">Bursa Kerja Khusus (BKK)</h2>
        
        <!-- Form Pencarian Native -->
        <form action="{{ route('lowongan.index') ?? '#' }}" method="GET" class="flex flex-wrap gap-3 items-center w-full">
            <input type="search" name="q" class="ui-input flex-1 min-w-[250px]" placeholder="Cari posisi / lokasi..." value="{{ request('q') }}">
            <button type="submit" class="ui-btn ui-btn-primary">
                🔍 Cari
            </button>
        </form>
    </div>

    <div class="job-grid">
        @if(isset($lowongans) && count($lowongans) > 0)
            @foreach($lowongans as $job)
                <article class="card job-card">
                    <div class="badge">Terbaru</div>
                    <div class="job-header">
                        <img src="{{ $job->perusahaan->logo_path ? asset('storage/'.$job->perusahaan->logo_path) : 'https://ui-avatars.com/api/?name='.urlencode($job->perusahaan->nama_perusahaan).'&background=e6f0fa&color=0056b3' }}" alt="Logo" class="company-logo">
                        <div>
                            <h3 class="job-title">{{ $job->judul }}</h3>
                            <div class="job-company">{{ $job->perusahaan->nama_perusahaan }}</div>
                        </div>
                    </div>
                    
                    <div class="job-meta">
                        <div class="meta-item">
                            <span>📍</span> {{ $job->lokasi_penempatan }}
                        </div>
                        <div class="meta-item">
                            <span>💰</span> Rp {{ $job->gaji_min_format }} - Rp {{ $job->gaji_max_format }}
                        </div>
                        <div class="meta-item">
                            <span>⏳</span> Ditutup: {{ $job->batas_lamaran->format('d M Y') }}
                        </div>
                    </div>
                    
                    <p class="job-description">
                        {{ Str::limit($job->deskripsi, 100) }}
                    </p>
                    
                    <div class="job-footer">
                        <form action="{{ Route::has('lamaran.store') ? route('lamaran.store', $job->id) : '#' }}" method="POST" id="form-lamar-{{ $job->id }}">
                            @csrf
                            <button type="button" class="ui-btn ui-btn-primary w-full" onclick="confirmLamar('{{ $job->judul }}', 'form-lamar-{{ $job->id }}')">
                                Lamar Sekarang 🚀
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        @else
            <article class="ui-panel empty-state ui-panel-center">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-2xl font-semibold text-slate-900 mb-3">Belum ada lowongan</h3>
                <p class="text-slate-500">Saat ini belum ada lowongan pekerjaan yang tersedia. Silakan cek kembali nanti.</p>
            </article>
        @endif
    </div>

    @if(isset($lowongans) && method_exists($lowongans, 'links'))
    <div class="mt-8">
        {{ $lowongans->links() }}
    </div>
    @endif
@endsection

@push('scripts')
<script>
    // Interaksi Vanilla JS untuk tombol Lamar
    function confirmLamar(judulLowongan, formId) {
        showConfirmDialog(
            `Apakah Anda yakin ingin mengirim CV dan melamar posisi "${judulLowongan}"? Pastikan profil Anda sudah diperbarui.`,
            function() {
                document.getElementById(formId).submit();
            }
        );
    }
</script>
@endpush
