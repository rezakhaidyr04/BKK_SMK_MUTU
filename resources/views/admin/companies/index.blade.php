<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Daftar Perusahaan" subtitle="Kelola dan verifikasi akun perusahaan mitra BKK." eyebrow="Admin › Perusahaan">
                        <x-slot:chips>
                @if(($pendingCount ?? 0) > 0)
                <span class="page-banner__chip">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ $pendingCount }} Menunggu Verifikasi
                </span>
                @endif
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $companies->total() }} Perusahaan · Kelola Perusahaan
                </span>
            </x-slot:chips>
            <x-slot:actions>
                @if($pendingCount > 0)
                <x-ui.status-badge :status="'pending'">{{ $pendingCount }} menunggu verifikasi</x-ui.status-badge>
                @endif
                <a href="{{ route('admin.companies.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Perusahaan
                </a>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

    <div class="ui-filter-bar">
        <form method="GET" action="{{ route('admin.companies.index') }}" class="flex flex-wrap gap-4 items-end w-full">
            <div class="ui-filter-field">
                <label class="ui-label">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" class="ui-input" placeholder="Nama, industri, alamat">
            </div>
            <div class="ui-filter-field">
                <label class="ui-label">Status Verifikasi</label>
                <select name="status" class="ui-select">
                    <option value="">Semua</option>
                    <option value="pending"  {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                    <option value="verified" {{ request('status') === 'verified'  ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="rejected" {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.btn type="submit">Saring</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.companies.index') }}">Atur Ulang</x-ui.btn>
            </div>
        </form>
    </div>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Industri</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr class="{{ $company->verification_status === 'pending' ? 'ui-table-row-highlight' : '' }}">
                        <td>
                            <p class="font-semibold text-slate-900">{{ $company->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Daftar {{ $company->created_at->format('d M Y') }}</p>
                        </td>
                        <td>{{ $company->industry ?? '-' }}</td>
                        <td>
                            {{ optional($company->user)->email ?? $company->email ?? '-' }}
                            @if(!$company->user_id)
                            <span class="ml-1 text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">No Akun</span>
                            @endif
                        </td>
                        <td>
                            <x-ui.status-badge :status="$company->verification_status" />
                            @if($company->mou_path)
                            <span class="block mt-1 text-xs text-green-600 font-medium">Ada MoU</span>
                            <a href="{{ route('admin.companies.mou.download', $company) }}"
                               class="inline-flex items-center gap-1 mt-1 text-xs text-blue-600 font-semibold hover:text-blue-800 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat MOU
                            </a>
                            @endif
                            @if($company->verification_status === 'rejected' && $company->rejection_reason)
                            <p class="text-xs text-red-500 mt-1 max-w-[180px]">{{ Str::limit($company->rejection_reason, 50) }}</p>
                            @endif
                        </td>
                        <td>
                            <div class="ui-table-actions flex-wrap">
                                @if($company->verification_status !== 'verified')
                                <form method="POST" action="{{ route('admin.companies.approve', $company) }}" data-confirm="Setujui verifikasi {{ $company->name }}?" data-confirm-title="Setujui" data-confirm-ok="Setujui">
                                    @csrf
                                    <x-ui.btn type="submit" variant="success" size="sm">
                                        Setujui
                                    </x-ui.btn>
                                </form>
                                @endif

                                @if($company->verification_status !== 'rejected')
                                <x-ui.btn variant="danger" size="sm" type="button"
                                    onclick="openRejectModal({{ $company->id }}, {{ Js::from($company->name) }})">
                                    Tolak
                                </x-ui.btn>
                                @endif

                                @if($company->mou_path)
                                <x-ui.btn href="{{ route('admin.companies.mou.download', $company) }}" target="_blank" variant="secondary" size="sm" title="Lihat MOU">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat MOU
                                </x-ui.btn>
                                @endif

                                <x-ui.btn href="{{ route('admin.companies.show', $company) }}" variant="secondary" size="sm">Detail</x-ui.btn>
                                <x-ui.btn href="{{ route('admin.companies.edit', $company) }}" variant="secondary" size="sm">Edit</x-ui.btn>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <x-ui.empty-state
                                title="Tidak ada perusahaan ditemukan"
                                description="Coba ubah filter pencarian atau tunggu pendaftaran perusahaan baru."
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $companies->links() }}
        </div>
    </x-ui.panel>

    <x-ui.modal id="rejectModal" title="Tolak Verifikasi">
        <p class="text-sm text-slate-500 mb-4">Perusahaan: <span id="rejectCompanyName" class="font-semibold text-slate-800"></span></p>

        <form id="rejectForm" method="POST" class="ui-form-stack">
            @csrf
            <div>
                <label class="ui-label">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" rows="4" required maxlength="500" class="ui-textarea"
                          placeholder="Contoh: Profil perusahaan belum lengkap. Mohon isi industri, alamat, dan deskripsi perusahaan terlebih dahulu."></textarea>
                <p class="text-xs text-slate-400 mt-1">Alasan ini akan ditampilkan ke perusahaan. Maks 500 karakter.</p>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn variant="danger" type="submit">Kirim Penolakan</x-ui.btn>
                <x-ui.btn variant="secondary" type="button" onclick="closeRejectModal()">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.modal>

    @push('scripts')
    <script>
    function openRejectModal(companyId, companyName) {
        document.getElementById('rejectCompanyName').textContent = companyName;
        document.getElementById('rejectForm').action = `/admin/companies/${companyId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectForm').querySelector('textarea').value = '';
    }
    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', closeRejectModal);
    });
    </script>
    @endpush
        </div>
    </div>
</x-app-layout>
