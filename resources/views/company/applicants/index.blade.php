<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Pelamar" subtitle="Kelola semua pelamar untuk lowongan perusahaan Anda.">
            <x-slot:actions>
                <form action="{{ route('company.applicants.index') }}" method="GET" class="flex gap-2 items-center">
                    <select name="status" class="ui-select ui-btn-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="submitted"    {{ request('status') === 'submitted'    ? 'selected' : '' }}>Terkirim</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Ditinjau</option>
                        <option value="interviewed"  {{ request('status') === 'interviewed'  ? 'selected' : '' }}>Wawancara</option>
                        <option value="accepted"     {{ request('status') === 'accepted'     ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected"     {{ request('status') === 'rejected'     ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </form>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="py-2">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 space-y-4">
                {{-- Bulk Actions Bar --}}
                <div id="bulkActionsBar" class="hidden bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500" onchange="toggleAllCheckboxes()">
                            <span class="text-sm font-medium text-purple-900 dark:text-purple-100">
                                <span id="selectedCount">0</span> pelamar dipilih
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('company.applicants.bulkUpdate') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="under_review">
                                <input type="hidden" name="selected_applications" id="bulkUnderReview">
                                <button type="button" onclick="submitBulkAction('under_review')" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition">
                                    Tinjau Semua
                                </button>
                            </form>
                            <form action="{{ route('company.applicants.bulkUpdate') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="selected_applications" id="bulkReject">
                                <button type="button" onclick="submitBulkAction('rejected')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition">
                                    Tolak Semua
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                    @forelse($applications as $application)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <input type="checkbox" class="applicant-checkbox w-4 h-4 text-purple-600 rounded focus:ring-purple-500 mt-1" value="{{ $application->id }}" onchange="updateBulkActions()">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-slate-900">
                                        {{ $application->job->title ?? 'Lowongan' }}
                                    </h3>
                                    <p class="text-sm text-slate-600 mt-0.5">
                                        Pelamar: <span class="font-medium text-slate-800">{{ $application->user->name ?? '-' }}</span>
                                    </p>
                                    @if($application->cover_letter)
                                    <p class="text-sm text-slate-500 mt-2 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($application->cover_letter, 150) }}
                                    </p>
                                    @endif
                                    @if($application->attachment_path)
                                    <p class="text-sm text-blue-600 mt-2">
                                        Lampiran: <a href="{{ asset('storage/' . $application->attachment_path) }}" target="_blank" class="font-semibold hover:underline">{{ $application->attachment_name ?? 'Buka berkas' }}</a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                <span class="text-xs text-slate-400">{{ $application->created_at->format('d M Y') }}</span>
                                @php
                                    $statusMap = [
                                        'submitted'    => ['bg-blue-100 text-blue-700',   'Terkirim'],
                                        'under_review' => ['bg-yellow-100 text-yellow-700','Ditinjau'],
                                        'interviewed'  => ['bg-purple-100 text-purple-700','Wawancara'],
                                        'accepted'     => ['bg-green-100 text-green-700',  'Diterima'],
                                        'rejected'     => ['bg-red-100 text-red-700',      'Ditolak'],
                                    ];
                                    [$cls, $label] = $statusMap[$application->status] ?? ['bg-slate-100 text-slate-700', $application->status];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="mt-4 flex flex-wrap gap-2 pt-3 border-t border-slate-100">
                            <x-ui.btn variant="secondary" size="sm" href="{{ route('applications.show', $application) }}">Lihat Detail</x-ui.btn>

                            @if(in_array($application->status, ['submitted', 'under_review']))
                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="under_review">
                                <x-ui.btn size="sm" type="submit" class="!bg-yellow-500 hover:!bg-yellow-600 text-white">Tinjau</x-ui.btn>
                            </form>

                            <x-ui.btn size="sm" href="{{ route('company.applicants.interview.form', $application) }}" class="!bg-purple-600 hover:!bg-purple-700 text-white">
                                📅 Jadwalkan Wawancara
                            </x-ui.btn>
                            @endif

                            @if($application->status === 'interviewed')
                            <x-ui.btn size="sm" variant="secondary" href="{{ route('company.applicants.interview.form', $application) }}" class="!text-purple-700 !border-purple-300 hover:!bg-purple-50">
                                📅 Ubah Jadwal
                            </x-ui.btn>
                            @endif

                            @if(in_array($application->status, ['submitted', 'under_review', 'interviewed']))
                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="accepted">
                                <x-ui.btn size="sm" type="submit" class="!bg-emerald-600 hover:!bg-emerald-700 text-white" onclick="return confirm('Terima lamaran dari {{ addslashes($application->user->name ?? '') }}?')">
                                    Terima
                                </x-ui.btn>
                            </form>

                            <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <x-ui.btn variant="danger" size="sm" type="submit" onclick="return confirm('Tolak lamaran ini?')">
                                    Tolak
                                </x-ui.btn>
                            </form>
                            @endif

                            @if($application->status === 'accepted')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Diterima
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <x-ui.empty-state
                        title="Belum ada pelamar"
                        description="{{ request('status') ? 'Tidak ada pelamar dengan status ' . ($statusMap[request('status')][1] ?? request('status')) : 'Pastikan lowongan sudah aktif agar pelamar bisa menemukannya.' }}"
                    />
                    @endforelse

                    {{-- Pagination --}}
                    @if($applications->hasPages())
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                    @endif
                </div>

                {{-- Sidebar Ringkasan --}}
                <aside class="space-y-6">
                    <x-ui.panel>
                        <h3 class="text-sm font-semibold text-slate-900 mb-4">Ringkasan Status</h3>
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">Total</span>
                                <span class="font-bold text-slate-900">{{ $stats['total'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-t border-slate-50">
                                <span class="text-blue-600">Terkirim</span>
                                <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['submitted'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-600">Ditinjau</span>
                                <span class="font-semibold text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['under_review'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-600">Wawancara</span>
                                <span class="font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['interviewed'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-green-600">Diterima</span>
                                <span class="font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['accepted'] ?? 0 }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-red-500">Ditolak</span>
                                <span class="font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full text-xs">
                                    {{ $stats['rejected'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </x-ui.panel>

                    {{-- Link cepat --}}
                    <x-ui.panel>
                        <h3 class="text-sm font-semibold text-slate-900 mb-3">Filter Cepat</h3>
                        <div class="space-y-1.5">
                            <a href="{{ route('company.applicants.index') }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-slate-50 transition {{ !request('status') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600' }}">
                                <span>Semua</span>
                                <span class="text-xs font-bold">{{ $stats['total'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'submitted']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-blue-50 transition {{ request('status') === 'submitted' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600' }}">
                                <span>Terkirim</span>
                                <span class="text-xs font-bold">{{ $stats['submitted'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'interviewed']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-purple-50 transition {{ request('status') === 'interviewed' ? 'bg-purple-50 text-purple-700 font-medium' : 'text-slate-600' }}">
                                <span>Wawancara</span>
                                <span class="text-xs font-bold">{{ $stats['interviewed'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('company.applicants.index', ['status' => 'accepted']) }}"
                               class="flex items-center justify-between text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition {{ request('status') === 'accepted' ? 'bg-green-50 text-green-700 font-medium' : 'text-slate-600' }}">
                                <span>Diterima</span>
                                <span class="text-xs font-bold">{{ $stats['accepted'] ?? 0 }}</span>
                            </a>
                        </div>
                    </x-ui.panel>
                </aside>
            </div>
        </div>
    </div>

    <script>
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.applicant-checkbox');
            const selected = Array.from(checkboxes).filter(cb => cb.checked);
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            
            if (selected.length > 0) {
                bulkActionsBar.classList.remove('hidden');
                selectedCount.textContent = selected.length;
            } else {
                bulkActionsBar.classList.add('hidden');
            }
        }

        function toggleAllCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.applicant-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkActions();
        }

        function submitBulkAction(status) {
            const checkboxes = document.querySelectorAll('.applicant-checkbox:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu pelamar');
                return;
            }

            const inputId = status === 'under_review' ? 'bulkUnderReview' : 'bulkReject';
            document.getElementById(inputId).value = JSON.stringify(selectedIds);
            
            const form = document.getElementById(inputId).closest('form');
            if (confirm(`Apakah Anda yakin ingin mengubah status ${selectedIds.length} pelamar menjadi ${status === 'under_review' ? 'Ditinjau' : 'Ditolak'}?`)) {
                form.submit();
            }
        }
    </script>
</x-app-layout>
