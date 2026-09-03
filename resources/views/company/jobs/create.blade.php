<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Buat Lowongan" subtitle="Tambah lowongan baru untuk perusahaan Anda.">
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary">Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <div class="page-shell">
        <div class="page-container page-section max-w-5xl mx-auto">
            @if($errors->any())
                <x-ui.alert type="danger" class="mb-6">
                    <div class="space-y-2">
                        <p class="font-semibold">Ada beberapa kesalahan pada formulir.</p>
                        <ul class="mt-2 list-disc pl-5 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </x-ui.alert>
            @endif

            <form method="POST" action="{{ route('company.jobs.store') }}" class="grid gap-6">
                @csrf
                <input type="hidden" name="company_name" value="{{ auth()->user()->company?->name ?? '' }}">

                <x-ui.panel title="Informasi Lowongan" subtitle="Lengkapi detail dasar lowongan terlebih dahulu." class="job-form-panel job-form-panel-primary">
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">1</span>
                    </x-slot>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="ui-label">Judul Lowongan <span class="text-red-600">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="ui-input">
                            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Posisi</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="ui-input">
                            @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="ui-input">
                            @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Tipe Kerja</label>
                            <select name="job_type" class="ui-select">
                                <option value="full_time" {{ old('job_type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                <option value="part_time" {{ old('job_type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                <option value="internship" {{ old('job_type') === 'internship' ? 'selected' : '' }}>Magang</option>
                                <option value="contract" {{ old('job_type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('job_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Minimum</label>
                            <input type="number" name="salary_min" value="{{ old('salary_min') }}" min="0" class="ui-input">
                            @error('salary_min')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Maksimum</label>
                            <input type="number" name="salary_max" value="{{ old('salary_max') }}" min="0" class="ui-input">
                            @error('salary_max')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" class="ui-input">
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="job-form-notice rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm text-blue-800">
                            Lowongan yang Anda buat akan berstatus <strong>Menunggu Persetujuan</strong> dan baru dipublikasikan setelah diverifikasi oleh admin BKK.
                        </div>
                    </div>
                </x-ui.panel>

                <x-ui.panel title="Rincian Lowongan" subtitle="Tambahkan kualifikasi, benefit, dan deskripsi lengkap pekerjaan." class="job-form-panel job-form-panel-secondary">
                    <x-slot name="header">
                        <span class="job-form-section-icon" aria-hidden="true">2</span>
                    </x-slot>
                    <div class="grid gap-5">
                        <div>
                            <label class="ui-label">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" class="ui-textarea">{{ old('qualifications') }}</textarea>
                            @error('qualifications')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Benefit</label>
                            <textarea name="benefits" rows="3" class="ui-textarea">{{ old('benefits') }}</textarea>
                            @error('benefits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="6" class="ui-textarea">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <div class="job-form-actions flex flex-col gap-3 sm:flex-row sm:justify-end mt-4">
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" class="w-full sm:w-auto">Batal</x-ui.btn>
                    <x-ui.btn type="submit" variant="company" class="w-full sm:w-auto">Simpan Lowongan</x-ui.btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
