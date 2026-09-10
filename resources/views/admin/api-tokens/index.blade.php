<x-app-layout title="Token API — BKK SMK MUTU" description="Buat personal access token (Sanctum) untuk mengakses API BKK SMK MUTU." :full-bleed="true">
    <div class="page-shell">
        <x-ui.page-banner title="Token API" subtitle="Buat personal access token untuk mengakses API dari aplikasi eksternal." eyebrow="Admin › API">
                        <x-slot:chips>
                <span class="page-banner__chip">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    API Sanctum
                </span>
                <span class="page-banner__chip">Admin Area</span>
            </x-slot:chips>
            <x-slot:actions>
                <x-ui.btn variant="white" size="sm" href="{{ route('admin.activities.index') }}">Kembali ke Log</x-ui.btn>
            </x-slot:actions>
        </x-ui.page-banner>
        <div class="page-container page-section">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <form method="POST" action="{{ route('admin.api-tokens.store') }}">
                    @csrf
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Token (opsional)</label>
                    <div class="flex gap-3">
                        <input type="text" name="token_name" placeholder="mis. aplikasi-mobile"
                               class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:bg-blue-700">Buat Token</button>
                    </div>
                </form>

                @if (session('api_token'))
                    <div class="mt-4 rounded-lg bg-green-50 border border-green-200 p-4">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                        <code class="block mt-2 break-all text-sm text-green-900">{{ session('api_token') }}</code>
                        <p class="mt-2 text-xs text-green-700">Gunakan sebagai header: <code>Authorization: Bearer &lt;token&gt;</code></p>
                    </div>
                @endif
            </div>

            <div class="text-sm text-slate-500">
                Endpoint publik: <code>GET /api/jobs</code> · <code>GET /api/jobs/{id}</code><br>
                Endpoint terlindungi: <code>GET /api/user</code> (butuh token).
            </div>
        </div>
    </div>
</x-app-layout>
