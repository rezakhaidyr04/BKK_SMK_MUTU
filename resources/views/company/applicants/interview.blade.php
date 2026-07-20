<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Jadwalkan Wawancara" subtitle="Pelamar: {{ $application->user->name }} — Posisi: {{ $application->job->title }}">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('company.applicants.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <x-ui.panel>
            <div class="mb-6 p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg flex-shrink-0">
                    {{ strtoupper(substr($application->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-900 truncate">{{ $application->user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $application->user->email }}</p>
                    @if($application->user->phone)
                        <p class="text-sm text-slate-500">{{ $application->user->phone }}</p>
                    @endif
                </div>
                <div class="text-right flex-shrink-0 hidden sm:block">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        {{ $application->job->title }}
                    </span>
                    <p class="text-xs text-slate-400 mt-1">Melamar {{ $application->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <form action="{{ route('company.applicants.interview.schedule', $application) }}" method="POST" class="ui-form-stack">
                @csrf
                <x-ui.form-errors />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="ui-label" for="interview_date">
                            📅 Tanggal & Jam Wawancara <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local"
                               id="interview_date"
                               name="interview_date"
                               value="{{ old('interview_date', $application->interview_date?->format('Y-m-d\TH:i')) }}"
                               min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                               required
                               class="ui-input">
                        @error('interview_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="ui-label" for="interview_type">
                            🏢 Tipe Wawancara <span class="text-red-500">*</span>
                        </label>
                        <select name="interview_type" id="interview_type" required
                                onchange="toggleOnlineFields(this.value)"
                                class="ui-select">
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

                <div>
                    <label class="ui-label" for="interview_location">
                        📍 Tempat / Alamat Wawancara <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="interview_location"
                           name="interview_location"
                           value="{{ old('interview_location', $application->interview_location) }}"
                           placeholder="Contoh: Kantor PT Maju Bersama, Jl. Industri No. 12 / via Zoom"
                           required
                           class="ui-input">
                    @error('interview_location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="online_fields" x-cloak class="ui-hidden" x-bind:class="{ 'ui-hidden': {{ old('interview_type', $application->interview_type) != 'online' ? 'true' : 'false' }} }">
                    <label class="ui-label" for="interview_link">
                        🔗 Link Wawancara Online <span class="text-red-500">*</span>
                        <span class="text-slate-400 font-normal ml-1">(Zoom / Google Meet)</span>
                    </label>
                    <input type="url"
                           id="interview_link"
                           name="interview_link"
                           value="{{ old('interview_link', $application->interview_link) }}"
                           placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                           class="ui-input">
                    @error('interview_link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="ui-label" for="interview_notes">
                        📝 Catatan Tambahan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                    </label>
                    <textarea id="interview_notes" name="interview_notes" rows="4"
                              placeholder="Contoh: Harap membawa CV, KTP, dan ijazah asli..."
                              class="ui-textarea">{{ old('interview_notes', $application->interview_notes) }}</textarea>
                </div>

                <x-ui.alert type="info">
                    Setelah disimpan, notifikasi jadwal wawancara akan dikirim otomatis ke email <strong>{{ $application->user->email }}</strong>.
                    Status lamaran akan berubah menjadi <strong>Wawancara</strong>.
                </x-ui.alert>

                <div class="ui-form-actions mt-4 pt-4 border-t border-slate-100">
                    <x-ui.btn type="submit">Kirim Jadwal Wawancara</x-ui.btn>
                    <x-ui.btn variant="secondary" href="{{ route('company.applicants.index') }}">Batal</x-ui.btn>
                </div>
            </form>
        </x-ui.panel>
    </div>

    @push('scripts')
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
    @endpush
</x-app-layout>
