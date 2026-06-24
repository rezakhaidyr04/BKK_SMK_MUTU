<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Peserta Acara</h1>
                        <p class="text-purple-100">{{ $event->title }}</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            <!-- Info Acara -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-1">Total Pendaftar</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $registrations->total() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-1">Terdaftar</p>
                    <p class="text-4xl font-bold text-green-600">{{ $event->registrations()->where('status','registered')->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500 mb-1">Dibatalkan</p>
                    <p class="text-4xl font-bold text-red-500">{{ $event->registrations()->where('status','cancelled')->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500 mb-1">Tanggal Acara</p>
                    <p class="text-lg font-bold text-gray-900">{{ $event->start_time->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $event->start_time->format('H:i') }} WIB</p>
                </div>
            </div>

            <!-- Tabel Peserta -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Peserta</h3>
                    <span class="text-sm text-gray-500">{{ $registrations->total() }} orang</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($registrations as $reg)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($reg->user->avatar)
                                        <img src="{{ asset('storage/' . $reg->user->avatar) }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                            {{ substr($reg->user->name, 0, 1) }}
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $reg->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $reg->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleColors = ['student'=>'bg-blue-100 text-blue-700','alumni'=>'bg-green-100 text-green-700','teacher'=>'bg-yellow-100 text-yellow-700','company'=>'bg-purple-100 text-purple-700'];
                                        $roleLabels = ['student'=>'Siswa','alumni'=>'Alumni','teacher'=>'Guru','company'=>'Perusahaan'];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleColors[$reg->user->role] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $roleLabels[$reg->user->role] ?? $reg->user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->status === 'registered')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Terdaftar</span>
                                    @elseif($reg->status === 'cancelled')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Dibatalkan</span>
                                    @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Hadir</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $reg->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $reg->registered_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-gray-500">Belum ada peserta yang mendaftar.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($registrations->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $registrations->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
