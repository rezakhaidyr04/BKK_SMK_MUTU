<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Mulai perjalanan karir Anda bersama BKK SMK MUTU</p>
    </div>

    @if($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3.5 text-sm text-red-700">
        <p class="font-semibold mb-1">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif



    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Pilih role --}}
        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">
                Daftar Sebagai
            </label>
            <select id="role" name="role" class="ui-select" onchange="toggleCompanyFields(this.value)">
                <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Siswa</option>
                <option value="company" {{ old('role') === 'company' ? 'selected' : '' }}>Perusahaan</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                Nama Lengkap
            </label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   autocomplete="name"
                   placeholder="Nama lengkap Anda"
                   class="ui-input">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   placeholder="email@contoh.com"
                   class="ui-input">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Field khusus siswa --}}
        <div id="student-fields" class="space-y-5">
            <div>
                <label for="nis" class="block text-sm font-medium text-gray-700 mb-1.5">
                    NIS <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input id="nis"
                       type="text"
                       name="nis"
                       value="{{ old('nis') }}"
                       placeholder="Nomor Induk Siswa"
                       class="ui-input">
                <x-input-error :messages="$errors->get('nis')" class="mt-1.5" />
            </div>

            <div>
                <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tahun Lulus <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <select id="graduation_year" name="graduation_year"
                        class="ui-select">
                    <option value="">Pilih tahun lulus</option>
                    @for($year = date('Y') + 2; $year >= date('Y') - 10; $year--)
                        <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('graduation_year')" class="mt-1.5" />
            </div>
        </div>

        {{-- Field khusus perusahaan --}}
        <div id="company-fields" class="space-y-5 hidden">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Perusahaan
                </label>
                <input id="company_name"
                       type="text"
                       name="company_name"
                       value="{{ old('company_name') }}"
                       placeholder="Contoh: PT Sukses Mandiri"
                       class="ui-input">
                <x-input-error :messages="$errors->get('company_name')" class="mt-1.5" />
            </div>

            <div>
                <label for="company_industry" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Bidang Usaha <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input id="company_industry"
                       type="text"
                       name="company_industry"
                       value="{{ old('company_industry') }}"
                       placeholder="Contoh: Teknologi, Manufaktur"
                       class="ui-input">
            </div>

            <div>
                <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Alamat <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input id="company_address"
                       type="text"
                       name="company_address"
                       value="{{ old('company_address') }}"
                       placeholder="Alamat kantor"
                       class="ui-input">
            </div>

            <div>
                <label for="company_website" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Website <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input id="company_website"
                       type="url"
                       name="company_website"
                       value="{{ old('company_website') }}"
                       placeholder="https://contoh.com"
                       class="ui-input">
            </div>
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="ui-input">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                Konfirmasi Kata Sandi
            </label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   placeholder="Ulangi kata sandi"
                   class="ui-input">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full ui-btn ui-btn-primary mt-2">
            Buat Akun
        </button>
    </form>

    <script>
        function toggleCompanyFields(role) {
            const studentFields = document.getElementById('student-fields');
            const companyFields = document.getElementById('company-fields');

            if (!studentFields || !companyFields) {
                return;
            }

            const isCompany = role === 'company';
            studentFields.classList.toggle('hidden', isCompany);
            companyFields.classList.toggle('hidden', !isCompany);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            if (roleSelect) {
                toggleCompanyFields(roleSelect.value);
            }
        });
    </script>

    <p class="mt-6 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
            Masuk di sini
        </a>
    </p>
</x-guest-layout>
