<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Perusahaan</h2>
                <p class="text-sm text-gray-500">Kelola dan verifikasi akun perusahaan.</p>
            </div>
            @if($pendingCount > 0)
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 border border-yellow-300 rounded-lg text-sm font-semibold text-yellow-800">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ $pendingCount }} perusahaan menunggu verifikasi
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white shadow sm:rounded-xl p-5">
                <form method="GET" action="{{ route('admin.companies.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Nama, industri, alamat">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status Verifikasi</label>
                        <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua</option>
                            <option value="pending"  {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ request('status') === 'verified'  ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700">Saring</button>
                    <a href="{{ route('admin.companies.index') }}" class="px-4 py-2 border border-gray-300 text-sm text-gray-700 rounded-lg hover:bg-gray-50">Reset</a>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white shadow sm:rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Industri</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($companies as $company)
                        <tr class="{{ $company->verification_status === 'pending' ? 'bg-yellow-50' : 'hover:bg-gray-50' }} transition">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $company->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Daftar {{ $company->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $company->industry ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ optional($company->user)->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($company->verification_status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Terverifikasi
                                    </span>
                                @elseif($company->verification_status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Ditolak
                                    </span>
                                    @if($company->rejection_reason)
                                    <p class="text-xs text-red-500 mt-1 max-w-[180px]">{{ Str::limit($company->rejection_reason, 50) }}</p>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    {{-- Tombol Setujui --}}
                                    @if($company->verification_status !== 'verified')
                                    <form method="POST" action="{{ route('admin.companies.approve', $company) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition"
                                                onclick="return confirm('Setujui verifikasi {{ $company->name }}?')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Setujui
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Tombol Tolak --}}
                                    @if($company->verification_status !== 'rejected')
                                    <button type="button"
                                            onclick="openRejectModal({{ $company->id }}, '{{ addslashes($company->name) }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tolak
                                    </button>
                                    @endif

                                    <a href="{{ route('admin.companies.show', $company) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.companies.edit', $company) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:underline">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                Tidak ada perusahaan ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden" style="background:rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Tolak Verifikasi</h3>
                <p class="text-sm text-gray-500 mb-4">Perusahaan: <span id="rejectCompanyName" class="font-semibold text-gray-800"></span></p>

                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="4" required maxlength="500"
                                  class="block w-full rounded-xl border-gray-300 text-sm focus:border-red-400 focus:ring-red-400"
                                  placeholder="Contoh: Profil perusahaan belum lengkap. Mohon isi industri, alamat, dan deskripsi perusahaan terlebih dahulu."></textarea>
                        <p class="text-xs text-gray-400 mt-1">Alasan ini akan ditampilkan ke perusahaan. Maks 500 karakter.</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl transition">
                            Kirim Penolakan
                        </button>
                        <button type="button" onclick="closeRejectModal()"
                                class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    // Tutup modal jika klik di luar
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
    </script>
    @endpush
</x-app-layout>
