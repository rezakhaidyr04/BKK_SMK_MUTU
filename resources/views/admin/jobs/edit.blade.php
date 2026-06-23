<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Lowongan</h2>
                <p class="text-sm text-gray-500">Perbarui detail lowongan kerja.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perusahaan</label>
                            <select name="company_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('company_id', $job->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" name="title" value="{{ old('title', $job->title) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Posisi</label>
                            <input type="text" name="position" value="{{ old('position', $job->position) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                                <input type="text" name="location" value="{{ old('location', $job->location) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipe kerja</label>
                                <select name="job_type" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih tipe</option>
                                    <option value="full_time" {{ old('job_type', $job->job_type) == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                    <option value="part_time" {{ old('job_type', $job->job_type) == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                    <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Magang</option>
                                    <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gaji Minimum</label>
                                <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gaji Maksimum</label>
                                <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline', optional($job->deadline)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $job->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('qualifications', $job->qualifications) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Benefit</label>
                            <textarea name="benefits" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('benefits', $job->benefits) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="5" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $job->description) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
                        <a href="{{ route('admin.jobs.index') }}" class="rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
