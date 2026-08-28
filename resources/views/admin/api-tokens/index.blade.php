<x-app-layout title="Token API — BKK SMK MUTU" description="Buat personal access token (Sanctum) untuk mengakses API BKK SMK MUTU.">
    <div class="page-shell">
        <div class="page-container py-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Token API</h1>
                    <p class="text-sm text-slate-500 mt-1">Buat personal access token untuk mengakses API (mis. <code>/api/jobs</code>) dari aplikasi eksternal.</p>
                </div>
                <a href="{{ route('admin.activities.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Kembali ke Log</a>
            </div>

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
