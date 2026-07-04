<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Buat Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Mulai perjalanan karir Anda bersama Mutu Career Center</p>
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

    @php $selectedRole = old('role', 'student'); @endphp
    <form method="POST" action="{{ route('register') }}" class="space-y-5"
          x-data="{ role: '{{ $selectedRole }}' }">
        @csrf

        {{-- Pilih Jenis Akun --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Daftar sebagai</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="student" x-model="role" class="sr-only"
                           {{ $selectedRole === 'student' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 p-3.5 rounded-xl border-2 transition-all"
                         :class="role === 'student'
                             ? 'border-blue-500 bg-blue-50'
                             : 'border-gray-200 hover:border-gray-300 bg-white'">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Siswa/Alumni</div>
                            <div class="text-xs text-gray-500">Cari kerja & bangun karir</div>
                        </div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="role" value="company" x-model="role" class="sr-only"
                           {{ $selectedRole === 'company' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 p-3.5 rounded-xl border-2 transition-all"
                         :class="role === 'company'
                             ? 'border-green-500 bg-green-50'
                             : 'border-gray-200 hover:border-gray-300 bg-white'">
                        <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Perusahaan</div>
                            <div class="text-xs text-gray-500">Pasang lowongan & rekrut</div>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        {{-- Nama --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5"
                   x-text="role === 'company' ? 'Nama Perusahaan' : 'Nama Lengkap'">
                Nama Lengkap
            </label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   autocomplete="name"
                   x-bind:placeholder="role === 'company' ? 'Nama perusahaan Anda' : 'Nama lengkap Anda'"
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
        <div x-show="role === 'student'" x-transition class="space-y-5">
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

    <p class="mt-6 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
            Masuk di sini
        </a>
    </p>
</x-guest-layout>
