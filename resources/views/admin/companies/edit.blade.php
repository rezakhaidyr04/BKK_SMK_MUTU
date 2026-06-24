<x-app-layout>
    <x-slot name="header">
        <x-ui.page-header title="Ubah Perusahaan" subtitle="Perbarui informasi profil perusahaan.">
            <x-slot:actions>
                <x-ui.btn variant="secondary" href="{{ route('admin.companies.index') }}" size="sm">← Kembali</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-header>
    </x-slot>

    <x-ui.form-errors :errors="$errors" />

    <x-ui.panel class="ui-form-narrow">
        <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="ui-form-stack">
            @csrf
            @method('PUT')

            <div>
                <label class="ui-label">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="ui-input" required>
            </div>
            <div>
                <label class="ui-label">Industri</label>
                <input type="text" name="industry" value="{{ old('industry', $company->industry) }}" class="ui-input">
            </div>
            <div>
                <label class="ui-label">Website</label>
                <input type="url" name="website" value="{{ old('website', $company->website) }}" class="ui-input">
            </div>
            <div>
                <label class="ui-label">Alamat</label>
                <input type="text" name="address" value="{{ old('address', $company->address) }}" class="ui-input">
            </div>
            <div>
                <label class="ui-label">Deskripsi</label>
                <textarea name="description" rows="4" class="ui-textarea">{{ old('description', $company->description) }}</textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $company->is_verified) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="is_verified" class="text-sm text-slate-700">Terverifikasi</label>
            </div>

            <div class="ui-form-actions">
                <x-ui.btn type="submit">Simpan</x-ui.btn>
                <x-ui.btn variant="secondary" href="{{ route('admin.companies.index') }}">Batal</x-ui.btn>
            </div>
        </form>
    </x-ui.panel>
</x-app-layout>
