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

                <x-ui.panel title="Informasi Lowongan" subtitle="Lengkapi detail dasar lowongan terlebih dahulu.">
                    <div class="ui-panel-body grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Lowongan <span class="text-red-600">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="ui-input w-full">
                            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Posisi</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="ui-input w-full">
                            @error('position')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi</label>
                            <input type="text" name="location" value="{{ old('location') }}" class="ui-input w-full">
                            @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Kerja</label>
                            <select name="job_type" class="ui-input w-full">
                                <option value="full_time" {{ old('job_type') === 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                <option value="part_time" {{ old('job_type') === 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                <option value="internship" {{ old('job_type') === 'internship' ? 'selected' : '' }}>Magang</option>
                                <option value="contract" {{ old('job_type') === 'contract' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('job_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Gaji Minimum</label>
                            <input type="number" name="salary_min" value="{{ old('salary_min') }}" min="0" class="ui-input w-full">
                            @error('salary_min')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Gaji Maksimum</label>
                            <input type="number" name="salary_max" value="{{ old('salary_max') }}" min="0" class="ui-input w-full">
                            @error('salary_max')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" class="ui-input w-full">
                            @error('deadline')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-red-600">*</span></label>
                            <select name="status" required class="ui-input w-full">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                            @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <x-ui.panel title="Rincian Lowongan" subtitle="Tambahkan kualifikasi, benefit, dan deskripsi lengkap pekerjaan.">
                    <div class="ui-panel-body grid gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" class="ui-textarea w-full">{{ old('qualifications') }}</textarea>
                            @error('qualifications')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Benefit</label>
                            <textarea name="benefits" rows="3" class="ui-textarea w-full">{{ old('benefits') }}</textarea>
                            @error('benefits')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="6" class="ui-textarea w-full">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.panel>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end mt-4">
                    <x-ui.btn href="{{ route('company.jobs.index') }}" variant="secondary" class="w-full sm:w-auto">Batal</x-ui.btn>
                    <x-ui.btn type="submit" variant="company" class="w-full sm:w-auto">Simpan Lowongan</x-ui.btn>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
