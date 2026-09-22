<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Edit Lowongan" subtitle="Perbarui detail lowongan milik perusahaan Anda." eyebrow="Perusahaan › Lowongan">
            <x-slot:actions>
                <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary">Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
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

            <form method="POST" action="{{ route('company.jobs.update', $job) }}" class="grid gap-6">
                @csrf
                @method('PUT')

                <x-ui.panel title="Informasi Lowongan" subtitle="Status dan kepemilikan tidak dapat diubah dari formulir ini." class="job-form-panel job-form-panel-primary">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="ui-label">Judul Lowongan <span class="text-red-600">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="ui-input">
                            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Posisi</label>
                            <input type="text" name="position" value="{{ old('position', $job->position) }}" class="ui-input">
                            @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location', $job->location) }}" class="ui-input">
                            @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Tipe Kerja</label>
                            <select name="job_type" class="ui-select">
                                @foreach(['full_time' => 'Penuh Waktu', 'part_time' => 'Paruh Waktu', 'internship' => 'Magang', 'contract' => 'Kontrak'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('job_type', $job->job_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('job_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Minimum</label>
                            <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" min="0" class="ui-input">
                            @error('salary_min')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Gaji Maksimum</label>
                            <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" min="0" class="ui-input">
                            @error('salary_max')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="ui-input">
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                            Status saat ini: <strong>{{ $job->status }}</strong>. Perubahan status hanya melalui persetujuan admin atau Tutup Lowongan.
                        </div>
                    </div>
                </x-ui.panel>

                <x-ui.panel title="Rincian Lowongan" class="job-form-panel job-form-panel-secondary">
                    <div class="grid gap-5">
                        <div>
                            <label class="ui-label">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" class="ui-textarea">{{ old('qualifications', $job->qualifications) }}</textarea>
                            @error('qualifications')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Benefit</label>
                            <textarea name="benefits" rows="3" class="ui-textarea">{{ old('benefits', $job->benefits) }}</textarea>
                            @error('benefits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="ui-label">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="6" class="ui-textarea">{{ old('description', $job->description) }}</textarea>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end mt-4">
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" class="w-full sm:w-auto">Batal</x-ui.btn>
                    <x-ui.btn type="submit" variant="company" class="w-full sm:w-auto">Simpan Perubahan</x-ui.btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
