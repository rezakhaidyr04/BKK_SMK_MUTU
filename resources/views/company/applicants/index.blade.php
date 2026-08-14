<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Pelamar" subtitle="Lihat pelamar yang sudah mengajukan lamaran untuk lowongan perusahaan Anda." />
    </x-slot>

    <x-ui.panel>
        <div class="ui-table-wrap -mx-6 -mt-6">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Pelamar</th>
                        <th>Lowongan</th>
                        <th>Posisi</th>
                        <th>Tanggal Melamar</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>
                            <p class="font-semibold text-slate-900">{{ $application->user->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $application->user->email }}</p>
                        </td>
                        <td class="font-medium text-slate-800">{{ optional($application->job)->title ?? '-' }}</td>
                        <td class="text-slate-600">{{ optional($application->job)->position ?? '-' }}</td>
                        <td class="text-sm text-slate-500">{{ $application->created_at->format('d M Y') }}</td>
                        <td>
                            <x-ui.status-badge :status="$application->status">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </x-ui.status-badge>
                        </td>
                        <td class="text-sm text-slate-500">
                            {{ $application->attachment_name ?? 'Tidak ada' }}
                        </td>
                        <td class="text-right">
                            <div x-data="{ open: false, status: '{{ $application->status }}', type: '{{ $application->interview_type ?? 'offline' }}' }">
                                <button type="button" @click="open = true" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Kelola Seleksi
                                </button>

                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 text-left" x-cloak style="display: none;">
                                    <div @click.away="open = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6 overflow-y-auto max-h-[90vh]">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold text-slate-800">Kelola Seleksi: {{ $application->user->name }}</h3>
                                            <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('company.applications.update', $application) }}">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Lamaran</label>
                                                    <select x-model="status" name="status" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                        <option value="submitted">Diajukan</option>
                                                        <option value="under_review">Sedang Ditinjau</option>
                                                        <option value="interviewed">Wawancara Terjadwal</option>
                                                        <option value="accepted">Lolos (Accepted)</option>
                                                        <option value="rejected">Tidak Lolos (Rejected)</option>
                                                    </select>
                                                </div>

                                                <div x-show="status === 'interviewed'" class="space-y-4 pt-4 border-t border-slate-100" style="display: none;">
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                                                            <input type="date" name="interview_date" value="{{ $application->interview_date ? $application->interview_date->format('Y-m-d') : '' }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-700 mb-1">Jam</label>
                                                            <input type="time" name="interview_time" value="{{ $application->interview_date ? $application->interview_date->format('H:i') : '' }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Wawancara</label>
                                                        <select x-model="type" name="interview_type" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                            <option value="offline">Tatap Muka (Offline)</option>
                                                            <option value="online">Online (Zoom/Meet)</option>
                                                        </select>
                                                    </div>

                                                    <div x-show="type === 'online'" style="display: none;">
                                                        <label class="block text-sm font-medium text-slate-700 mb-1">Link Wawancara (URL)</label>
                                                        <input type="url" name="interview_link" value="{{ $application->interview_link }}" placeholder="https://zoom.us/j/..." class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                    </div>

                                                    <div x-show="type === 'offline'">
                                                        <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Wawancara</label>
                                                        <input type="text" name="interview_location" value="{{ $application->interview_location }}" placeholder="Alamat lengkap / Ruangan" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Tambahan</label>
                                                        <textarea name="interview_notes" rows="2" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Misal: Harap membawa laptop">{{ $application->interview_notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-50">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-ui.empty-state
                                icon="briefcase"
                                title="Belum ada pelamar"
                                description="Pelamar akan muncul setelah ada lowongan aktif dan kandidat mengajukan lamaran."
                                ctaLabel="Kelola Lowongan"
                                ctaHref="{{ route('company.jobs.index') }}"
                                ctaVariant="secondary"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $applications->links() }}
        </div>
        @endif
    </x-ui.panel>
</x-app-layout>
