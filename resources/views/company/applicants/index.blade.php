<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-hero 
            title="Pelamar" 
            subtitle="Kelola kandidat yang mendaftar ke lowongan perusahaan Anda."
        />

        <div class="page-container page-section">
            <x-ui.panel>

        {{-- =========================================================
            HEADER / INTRO
        ========================================================== --}}
        <div class="mb-6 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-white p-5">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M17 20h5v-1a4 4 0 00-4-4h-1m-6 5H3v-1a4 4 0 014-4h3m5-10a4 4 0 11-8 0 4 4 0 018 0zm4 3a3 3 0 10-6 0 3 3 0 006 0z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Daftar Pelamar
                        </h2>

                        <p class="mt-1 text-sm text-slate-600">
                            Tinjau kandidat berdasarkan lowongan yang mereka lamar.
                        </p>
                    </div>

                </div>


                <a
                    href="{{ route('company.jobs.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m0 0L4 7m8 4v10"
                        />
                    </svg>

                    Kelola Lowongan
                </a>

            </div>

        </div>


        {{-- =========================================================
            TABLE
        ========================================================== --}}
        <div class="ui-table-wrap -mx-6">

            <table class="ui-table">

                <thead>
                    <tr>

                        <th>Pelamar</th>

                        <th>Lowongan</th>

                        <th>Tanggal Melamar</th>

                        <th>Status</th>

                        <th>Lampiran</th>

                        <th class="text-right">
                            Aksi
                        </th>

                    </tr>
                </thead>


                <tbody>

                    @forelse($applications as $application)

                        <tr>

                            {{-- =================================================
                                PELAMAR
                            ================================================== --}}
                            <td>

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-bold text-blue-600">
                                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">

                                        <p class="truncate font-semibold text-slate-900">
                                            {{ $application->user->name }}
                                        </p>

                                        <p class="mt-0.5 truncate text-xs text-slate-400">
                                            {{ $application->user->email }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                LOWONGAN
                            ================================================== --}}
                            <td>

                                @if($application->job)

                                    <a
                                        href="{{ route('jobs.show', $application->job) }}"
                                        class="group flex max-w-md items-start gap-3"
                                    >

                                        {{-- Icon --}}
                                        <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">

                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m0 0L4 7m8 4v10"
                                                />
                                            </svg>

                                        </div>


                                        <div class="min-w-0">

                                            <p class="truncate font-semibold text-slate-900 transition group-hover:text-blue-600">
                                                {{ $application->job->title }}
                                            </p>


                                            <p class="mt-1 text-xs text-slate-500">

                                                {{ $application->job->company_name ?? 'Perusahaan' }}

                                                @if($application->job->location)
                                                    <span class="mx-1 text-slate-300">
                                                        •
                                                    </span>

                                                    {{ $application->job->location }}
                                                @endif

                                            </p>


                                            {{-- CTA --}}
                                            <span class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-600">

                                                Lihat lowongan

                                                <svg
                                                    class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>

                                            </span>

                                        </div>

                                    </a>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Lowongan tidak tersedia
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                TANGGAL
                            ================================================== --}}
                            <td class="whitespace-nowrap">

                                <p class="text-sm font-medium text-slate-700">
                                    {{ $application->created_at->format('d M Y') }}
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    {{ $application->created_at->format('H:i') }} WIB
                                </p>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}
                            <td>

                                <x-ui.status-badge :status="$application->status">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </x-ui.status-badge>

                            </td>


                            {{-- =================================================
                                LAMPIRAN
                            ================================================== --}}
                            <td>

                                @if($application->attachment_name)

                                    <div class="flex max-w-[180px] items-center gap-2">

                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M14 3v6h6"
                                                />
                                            </svg>
                                        </div>

                                        <span
                                            class="truncate text-sm text-slate-600"
                                            title="{{ $application->attachment_name }}"
                                        >
                                            {{ $application->attachment_name }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Tidak ada
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                AKSI
                            ================================================== --}}
                            <td class="text-right">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- CHAT KANDIDAT --}}
                                    @if($application->user)
                                    <form action="{{ route('messages.start') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="recipient_id" value="{{ $application->user_id }}">
                                        <button
                                            type="submit"
                                            title="Chat kandidat"
                                            aria-label="Chat dengan {{ $application->user->name }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- DETAIL --}}
                                    <a
                                        href="{{ route('applications.show', $application) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7C20.268 16.057 16.477 19 12 19c-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>

                                        Detail

                                    </a>


                                    {{-- =================================================
                                        KELOLA SELEKSI
                                    ================================================== --}}
                                    <div
                                        x-data="{
                                            open: false,
                                            status: @js($application->status),
                                            type: @js($application->interview_type ?? 'offline')
                                        }"
                                    >

                                        <button
                                            type="button"
                                            @click="open = true"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700"
                                        >
                                            Kelola
                                        </button>


                                        {{-- MODAL --}}
                                        <div
                                            x-show="open"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm"
                                            style="display: none;"
                                        >

                                            <div
                                                @click.away="open = false"
                                                class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl"
                                            >

                                                {{-- MODAL HEADER --}}
                                                <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5">

                                                    <div class="min-w-0">

                                                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                                                            Kelola Seleksi
                                                        </p>

                                                        <h3 class="mt-1 truncate text-lg font-bold text-slate-900">
                                                            {{ $application->user->name }}
                                                        </h3>

                                                        <p class="mt-1 truncate text-sm text-slate-500">
                                                            {{ optional($application->job)->title ?? 'Lowongan' }}
                                                        </p>

                                                    </div>


                                                    <button
                                                        type="button"
                                                        @click="open = false"
                                                        class="ml-4 rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                                    >

                                                        <svg
                                                            class="h-5 w-5"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12"
                                                            />
                                                        </svg>

                                                    </button>

                                                </div>


                                                {{-- MODAL FORM --}}
                                                <form
                                                    method="POST"
                                                    action="{{ route('company.applications.update', $application) }}"
                                                >

                                                    @csrf
                                                    @method('PATCH')


                                                    <div class="max-h-[70vh] space-y-5 overflow-y-auto px-6 py-6">


                                                        {{-- STATUS --}}
                                                        <div>

                                                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                                                Status Lamaran
                                                            </label>

                                                            <select
                                                                x-model="status"
                                                                name="status"
                                                                class="w-full rounded-xl border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            >

                                                                <option value="submitted">
                                                                    Diajukan
                                                                </option>

                                                                <option value="under_review">
                                                                    Sedang Ditinjau
                                                                </option>

                                                                <option value="interviewed">
                                                                    Wawancara Terjadwal
                                                                </option>

                                                                <option value="accepted">
                                                                    Lolos / Diterima
                                                                </option>

                                                                <option value="rejected">
                                                                    Tidak Lolos / Ditolak
                                                                </option>

                                                            </select>

                                                        </div>


                                                        {{-- =================================================
                                                            INTERVIEW
                                                        ================================================== --}}
                                                        <div
                                                            x-show="status === 'interviewed'"
                                                            x-cloak
                                                            class="space-y-4 rounded-xl border border-purple-100 bg-purple-50 p-4"
                                                            style="display: none;"
                                                        >

                                                            <div>

                                                                <p class="text-sm font-bold text-purple-900">
                                                                    Jadwal Wawancara
                                                                </p>

                                                                <p class="mt-1 text-xs text-purple-700">
                                                                    Informasi ini akan dilihat oleh kandidat.
                                                                </p>

                                                            </div>


                                                            {{-- TANGGAL + JAM --}}
                                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                                                <div>

                                                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                        Tanggal
                                                                    </label>

                                                                    <input
                                                                        type="date"
                                                                        name="interview_date"
                                                                        value="{{ $application->interview_date ? $application->interview_date->format('Y-m-d') : '' }}"
                                                                        class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                    >

                                                                </div>


                                                                <div>

                                                                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                        Jam
                                                                    </label>

                                                                    <input
                                                                        type="time"
                                                                        name="interview_time"
                                                                        value="{{ $application->interview_date ? $application->interview_date->format('H:i') : '' }}"
                                                                        class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                    >

                                                                </div>

                                                            </div>


                                                            {{-- TIPE --}}
                                                            <div>

                                                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                    Tipe Wawancara
                                                                </label>

                                                                <select
                                                                    x-model="type"
                                                                    name="interview_type"
                                                                    class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                >

                                                                    <option value="offline">
                                                                        Tatap Muka (Offline)
                                                                    </option>

                                                                    <option value="online">
                                                                        Online (Zoom/Meet)
                                                                    </option>

                                                                </select>

                                                            </div>


                                                            {{-- ONLINE --}}
                                                            <div
                                                                x-show="type === 'online'"
                                                                x-cloak
                                                                style="display: none;"
                                                            >

                                                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                    Link Wawancara
                                                                </label>

                                                                <input
                                                                    type="url"
                                                                    name="interview_link"
                                                                    value="{{ $application->interview_link }}"
                                                                    placeholder="https://zoom.us/..."
                                                                    class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                >

                                                            </div>


                                                            {{-- OFFLINE --}}
                                                            <div
                                                                x-show="type === 'offline'"
                                                            >

                                                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                    Lokasi Wawancara
                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    name="interview_location"
                                                                    value="{{ $application->interview_location }}"
                                                                    placeholder="Contoh: Ruang HRD"
                                                                    class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                >

                                                            </div>


                                                            {{-- CATATAN --}}
                                                            <div>

                                                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                                                    Catatan Tambahan
                                                                </label>

                                                                <textarea
                                                                    name="interview_notes"
                                                                    rows="3"
                                                                    placeholder="Contoh: Harap membawa laptop dan dokumen pendukung."
                                                                    class="w-full rounded-lg border-slate-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                                                >{{ $application->interview_notes }}</textarea>

                                                            </div>

                                                        </div>

                                                    </div>


                                                    {{-- MODAL FOOTER --}}
                                                    <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">

                                                        <button
                                                            type="button"
                                                            @click="open = false"
                                                            class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                                                        >
                                                            Batal
                                                        </button>


                                                        <button
                                                            type="submit"
                                                            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                                                        >
                                                            Simpan Perubahan
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <x-ui.empty-state
                                    icon="briefcase"
                                    title="Belum ada pelamar"
                                    description="Pelamar akan muncul setelah kandidat mengajukan lamaran ke salah satu lowongan perusahaan Anda."
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


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($applications->hasPages())

            <div class="mt-6 border-t border-slate-100 pt-4">
                {{ $applications->links() }}
            </div>

        @endif

            </x-ui.panel>
        </div>
    </div>
</x-app-layout>
