@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <a href="{{ route('company.applicants.index') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Pelamar
    </a>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Jadwalkan Wawancara</h1>
                <p class="text-blue-100 text-sm mt-1">
                    Pelamar: <strong>{{ $application->user->name }}</strong>
                    &mdash; Posisi: <strong>{{ $application->job->title }}</strong>
                </p>
            </div>
        </div>
    </div>

    {{-- Info Pelamar --}}
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg flex-shrink-0">
            {{ strtoupper(substr($application->user->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-semibold text-gray-900">{{ $application->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
            @if($application->user->phone)
                <p class="text-sm text-gray-500">{{ $application->user->phone }}</p>
            @endif
        </div>
        <div class="ml-auto text-right">
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                {{ $application->job->title }}
            </span>
            <p class="text-xs text-gray-400 mt-1">Melamar {{ $application->created_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Detail Jadwal Wawancara
        </h2>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('company.applicants.interview.schedule', $application) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Tanggal & Jam --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        📅 Tanggal & Jam Wawancara <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local"
                           name="interview_date"
                           value="{{ old('interview_date', $application->interview_date?->format('Y-m-d\TH:i')) }}"
                           min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('interview_date') border-red-400 @enderror">
                    @error('interview_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        🏢 Tipe Wawancara <span class="text-red-500">*</span>
                    </label>
                    <select name="interview_type" id="interview_type" required
                            onchange="toggleOnlineFields(this.value)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="offline" {{ old('interview_type', $application->interview_type) == 'offline' ? 'selected' : '' }}>
                            🏢 Tatap Muka (Offline)
                        </option>
                        <option value="online" {{ old('interview_type', $application->interview_type) == 'online' ? 'selected' : '' }}>
                            💻 Online (Zoom / Google Meet)
                        </option>
                    </select>
                    @error('interview_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    📍 Tempat / Alamat Wawancara <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="interview_location"
                       value="{{ old('interview_location', $application->interview_location) }}"
                       placeholder="Contoh: Kantor PT Maju Bersama, Jl. Industri No. 12 Cikampek / via Zoom"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('interview_location') border-red-400 @enderror">
                @error('interview_location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link Online (tampil jika online) --}}
            <div id="online_fields" style="{{ old('interview_type', $application->interview_type) == 'online' ? '' : 'display:none' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    🔗 Link Wawancara Online
                    <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">(Zoom / Google Meet / dll)</span>
                </label>
                <input type="url"
                       name="interview_link"
                       value="{{ old('interview_link', $application->interview_link) }}"
                       placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('interview_link') border-red-400 @enderror">
                @error('interview_link')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan Tambahan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    📝 Catatan Tambahan
                    <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="interview_notes" rows="4"
                          placeholder="Contoh: Harap membawa CV, KTP, dan ijazah asli. Pakaian formal. Hadir 10 menit sebelum jadwal."
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none">{{ old('interview_notes', $application->interview_notes) }}</textarea>
            </div>

            {{-- Info pengiriman --}}
            <div class="p-4 bg-blue-50 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700">
                    Setelah disimpan, notifikasi jadwal wawancara akan dikirim otomatis ke email
                    <strong>{{ $application->user->email }}</strong>.
                    Status lamaran akan berubah menjadi <strong>Wawancara</strong>.
                </p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Jadwal Wawancara
                </button>
                <a href="{{ route('company.applicants.index') }}"
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleOnlineFields(value) {
    const onlineFields = document.getElementById('online_fields');
    onlineFields.style.display = value === 'online' ? 'block' : 'none';
}
// Init on load
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('interview_type');
    if (sel) toggleOnlineFields(sel.value);
});
</script>
@endsection
