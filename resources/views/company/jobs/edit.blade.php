<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Lowongan" subtitle="Perbarui informasi lowongan pekerjaan.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('company.jobs.index') }}" size="sm">
                    Kembali
                </x-ui.btn>
                <form action="{{ route('company.jobs.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <x-ui.btn variant="danger" size="sm" type="submit">Hapus Lowongan</x-ui.btn>
                </form>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.panel>
        <form action="{{ route('company.jobs.update', $job) }}" method="POST" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="ui-label" for="title">Judul Lowongan <span class="text-red-500">*</span></label>
                    <input id="title" name="title" value="{{ old('title', $job->title) }}" required class="ui-input" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="position">Posisi <span class="text-red-500">*</span></label>
                    <input id="position" name="position" value="{{ old('position', $job->position) }}" required class="ui-input" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="location">Lokasi <span class="text-red-500">*</span></label>
                    <input id="location" name="location" value="{{ old('location', $job->location) }}" required class="ui-input" />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="job_type">Tipe Pekerjaan <span class="text-red-500">*</span></label>
                    <select name="job_type" id="job_type" class="ui-select">
                        <option value="full_time" {{ old('job_type', $job->job_type) == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                        <option value="part_time" {{ old('job_type', $job->job_type) == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                        <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Magang</option>
                        <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                    <x-input-error :messages="$errors->get('job_type')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="salary_min">Gaji Minimum</label>
                    <input id="salary_min" name="salary_min" type="number" value="{{ old('salary_min', $job->salary_min) }}" class="ui-input" placeholder="Misal: 4000000" />
                    <x-input-error :messages="$errors->get('salary_min')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="salary_max">Gaji Maksimum</label>
                    <input id="salary_max" name="salary_max" type="number" value="{{ old('salary_max', $job->salary_max) }}" class="ui-input" placeholder="Misal: 8000000" />
                    <x-input-error :messages="$errors->get('salary_max')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <label class="ui-label" for="description">Deskripsi Lengkap</label>
                    <textarea id="description" name="description" rows="6" class="ui-textarea">{{ old('description', $job->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <label class="ui-label" for="qualifications">Kualifikasi <span class="text-red-500">*</span></label>
                    <textarea id="qualifications" name="qualifications" rows="4" required class="ui-textarea">{{ old('qualifications', $job->qualifications) }}</textarea>
                    <x-input-error :messages="$errors->get('qualifications')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <label class="ui-label" for="benefits">Benefit</label>
                    <textarea id="benefits" name="benefits" rows="3" class="ui-textarea">{{ old('benefits', $job->benefits) }}</textarea>
                    <x-input-error :messages="$errors->get('benefits')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="deadline">Tenggat Waktu</label>
                    <input id="deadline" name="deadline" type="date" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="ui-input" />
                    <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                </div>

                <div>
                    <label class="ui-label" for="status">Status</label>
                    <select id="status" name="status" class="ui-select">
                        <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draf</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>
            </div>

            <div class="ui-form-actions mt-6 pt-4 border-t border-slate-100">
                <x-ui.btn type="submit">Perbarui Lowongan</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('company.jobs.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
