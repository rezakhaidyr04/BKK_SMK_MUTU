<section class="space-y-6">
    <header class="border-b border-red-100 pb-5 mb-4">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-rose-500 text-white shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-red-500">Perhatian · Ireversibel</p>
                <h2 class="text-lg font-bold text-red-600 tracking-tight">
                    {{ __('Zona Bahaya') }}
                </h2>
            </div>
        </div>
        <p class="mt-3 text-sm text-slate-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
        </p>
    </header>

    <div class="bg-red-50/50 border border-red-100 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <h4 class="text-sm font-bold text-red-800">Hapus Akun Secara Permanen</h4>
            <p class="text-xs text-red-600/80">Amankan data Anda terlebih dahulu sebelum melanjutkan proses ini.</p>
        </div>
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="rounded-xl px-4 py-2.5 bg-red-600 hover:bg-red-700 shadow-sm transition inline-flex justify-center whitespace-nowrap"
        >{{ __('Hapus Akun Saya') }}</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                {{ __('Tindakan ini akan menghapus akun Anda beserta seluruh riwayat lamaran dan profil secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan.') }}
            </p>

            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Kata Sandi Konfirmasi') }}" class="text-slate-700 font-semibold mb-1" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 shadow-sm"
                    placeholder="{{ __('Ketik kata sandi Anda di sini') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl px-4 py-2">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="rounded-xl px-4 py-2 bg-red-600 hover:bg-red-700 transition">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
