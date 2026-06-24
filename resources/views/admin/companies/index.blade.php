<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Daftar Perusahaan" subtitle="Kelola dan verifikasi akun perusahaan.">
            <x-slot:actions>
                @if($pendingCount > 0)
                <span class="ui-badge ui-badge-yellow">
                    {{ $pendingCount }} menunggu verifikasi
                </span>
                @endif
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

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
                        <td>{{ optional($company->user)->email ?? '-' }}</td>
                        <td>
                            <x-ui.status-badge :status="$company->verification_status" />
                            @if($company->verification_status === 'rejected' && $company->rejection_reason)
                            <p class="text-xs text-red-500 mt-1 max-w-[180px]">{{ Str::limit($company->rejection_reason, 50) }}</p>
                            @endif
                        </td>
                        <td>
                            <div class="ui-table-actions flex-wrap">
                                @if($company->verification_status !== 'verified')
                                <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
                                    @csrf
                                    <x-ui.btn type="submit" size="sm" onclick="return confirm('Setujui verifikasi {{ addslashes($company->name) }}?')">
                                        Setujui
                                    </x-ui.btn>
                                </form>
                                @endif

                                @if($company->verification_status !== 'rejected')
                                <x-ui.btn variant="danger" size="sm" type="button"
                                    onclick="openRejectModal({{ $company->id }}, '{{ addslashes($company->name) }}')">
                                    Tolak
                                </x-ui.btn>
                                @endif

                                <a href="{{ route('admin.companies.show', $company) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                                <a href="{{ route('admin.companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
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
</x-app-layout>
