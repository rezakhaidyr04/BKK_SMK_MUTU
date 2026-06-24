<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Lowongan" subtitle="Perbarui detail lowongan kerja.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.jobs.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div>
                <label class="ui-label">Perusahaan</label>
                <select name="company_id" class="ui-select" required>
                    @foreach($companies as $id => $name)
                    <option value="{{ $id }}" {{ old('company_id', $job->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="ui-label">Judul</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" class="ui-input" required>
            </div>
            <div>
                <label class="ui-label">Posisi</label>
                <input type="text" name="position" value="{{ old('position', $job->position) }}" class="ui-input">
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="ui-label">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}" class="ui-input">
                </div>
                <div>
                    <label class="ui-label">Tipe Kerja</label>
                    <select name="job_type" class="ui-select">
                        <option value="">Pilih tipe</option>
                        <option value="full_time" {{ old('job_type', $job->job_type) == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                        <option value="part_time" {{ old('job_type', $job->job_type) == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                        <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Magang</option>
                        <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="ui-label">Gaji Minimum</label>
                    <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="ui-input" min="0">
                </div>
                <div>
                    <label class="ui-label">Gaji Maksimum</label>
                    <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="ui-input" min="0">
                </div>
            </div>
            <div>
                <label class="ui-label">Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="ui-input">
            </div>
            <div>
                <label class="ui-label">Status</label>
                <select name="status" class="ui-select" required>
                    <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $job->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <div>
                <label class="ui-label">Kualifikasi</label>
                <textarea name="qualifications" rows="3" class="ui-textarea">{{ old('qualifications', $job->qualifications) }}</textarea>
            </div>
            <div>
                <label class="ui-label">Benefit</label>
                <textarea name="benefits" rows="3" class="ui-textarea">{{ old('benefits', $job->benefits) }}</textarea>
            </div>
            <div>
                <label class="ui-label">Deskripsi Pekerjaan</label>
                <textarea name="description" rows="5" class="ui-textarea">{{ old('description', $job->description) }}</textarea>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Simpan</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.jobs.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
