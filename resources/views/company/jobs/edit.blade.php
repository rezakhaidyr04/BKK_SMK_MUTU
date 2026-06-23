@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Ubah Lowongan</h1>
        <p class="text-sm text-gray-600">Perbarui informasi lowongan.</p>
    </div>

    <x-card class="p-6">
        <form action="{{ route('company.jobs.update', $job) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="title" value="Judul Lowongan" />
                    <input id="title" name="title" value="{{ old('title', $job->title) }}" required
                        class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="position" value="Posisi" />
                    <x-text-input id="position" name="position" value="{{ old('position', $job->position) }}" class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label for="location" value="Lokasi" />
                    <x-text-input id="location" name="location" value="{{ old('location', $job->location) }}" class="w-full mt-1" />
                </div>

                <div>
                    <x-input-label for="job_type" value="Tipe Pekerjaan" />
                    <select name="job_type" id="job_type" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="full_time" {{ old('job_type', $job->job_type) == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                        <option value="part_time" {{ old('job_type', $job->job_type) == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                        <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Magang</option>
                        <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="salary_min" value="Gaji Minimum" />
                    <input id="salary_min" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>

                <div>
                    <x-input-label for="salary_max" value="Gaji Maximum" />
                    <input id="salary_max" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="description" value="Deskripsi" />
                    <textarea id="description" name="description" rows="6" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-3 min-h-[180px] resize-y focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('description', $job->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="qualifications" value="Kualifikasi" />
                    <textarea id="qualifications" name="qualifications" rows="4" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-3 min-h-[120px] resize-y focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('qualifications', $job->qualifications) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="benefits" value="Benefit" />
                    <textarea id="benefits" name="benefits" rows="3" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-3 min-h-[80px] resize-y focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('benefits', $job->benefits) }}</textarea>
                </div>

                <div>
                    <x-input-label for="deadline" value="Tenggat Waktu" />
                    <input id="deadline" name="deadline" type="date" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="w-full mt-1 rounded-lg border border-gray-200 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draf</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <x-primary-button type="submit">Perbarui</x-primary-button>
                <a href="{{ route('company.jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl font-semibold hover:bg-gray-50">Batal</a>
            </div>
        </form>

        <form action="{{ route('company.jobs.destroy', $job) }}" method="POST" class="mt-4" onsubmit="return confirm('Hapus lowongan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600">Hapus Lowongan</button>
        </form>
    </x-card>
</div>
@endsection
