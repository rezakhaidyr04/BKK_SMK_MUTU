<x-app-layout :full-bleed="true">
    <div class="page-shell">
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Buat Lowongan Baru</h1>
                        <p class="text-purple-100">Tambah lowongan kerja baru untuk perusahaan.</p>
                    </div>
                    <a href="{{ route('admin.jobs.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="page-container page-section">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 max-w-3xl mx-auto">
                <ul class="text-sm text-red-700 space-y-1 list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h3 class="font-bold text-gray-900">Form Lowongan Baru</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.jobs.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="Contoh: PT Teknologi Indonesia"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Software Engineer"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi</label>
                                <input type="text" name="position" value="{{ old('position') }}" placeholder="Contoh: Senior Developer"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                                <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Jakarta"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kerja</label>
                                <select name="job_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="">Pilih tipe</option>
                                    <option value="full_time"  {{ old('job_type') == 'full_time'  ? 'selected' : '' }}>Penuh Waktu</option>
                                    <option value="part_time"  {{ old('job_type') == 'part_time'  ? 'selected' : '' }}>Paruh Waktu</option>
                                    <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                                    <option value="contract"   {{ old('job_type') == 'contract'   ? 'selected' : '' }}>Kontrak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Minimum</label>
                                <input type="number" name="salary_min" value="{{ old('salary_min') }}" min="0" placeholder="Contoh: 5000000"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Maksimum</label>
                                <input type="number" name="salary_max" value="{{ old('salary_max') }}" min="0" placeholder="Contoh: 8000000"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deadline</label>
                                <input type="date" name="deadline" value="{{ old('deadline') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="closed"   {{ old('status') == 'closed'   ? 'selected' : '' }}>Ditutup</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kualifikasi</label>
                            <textarea name="qualifications" rows="3" placeholder="Contoh: Lulusan SMK Jurusan RPL, bisa PHP/JavaScript..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-y">{{ old('qualifications') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Benefit</label>
                            <textarea name="benefits" rows="3" placeholder="Contoh: BPJS, Tunjangan Transport, Makan Siang..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-y">{{ old('benefits') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Pekerjaan</label>
                            <textarea name="description" rows="5" placeholder="Deskripsi lengkap tentang pekerjaan..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-y">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.jobs.index') }}"
                               class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Buat Lowongan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
