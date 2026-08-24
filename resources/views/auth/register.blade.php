<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar sebagai Pencari Kerja</h2>
        <p class="mt-1 text-sm text-gray-500">Buat akun pencari kerja untuk mencari lowongan, membuat CV, dan melamar pekerjaan.</p>
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

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
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
