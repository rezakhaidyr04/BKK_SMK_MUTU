<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        // P0 H-08: tampilkan daftar token (tanpa plaintext) agar bisa direvoke.
        $tokens = $request->user()->tokens()->latest()->get();

        return view('admin.api-tokens.index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => ['nullable', 'string', 'max:255'],
        ]);

        $name = $request->input('token_name') ?: 'admin-token-' . now()->format('YmdHis');

        // P0 H-08: batasi ability + beri expiry 30 hari. Tanpa [*] unlimited.
        // API publik jobs:read saja; /api/user butuh auth (token valid).
        $token = $request->user()->createToken(
            $name,
            ['jobs:read'],
            now()->addDays(30)
        )->plainTextToken;

        return redirect()
            ->route('admin.api-tokens.index')
            ->with('api_token', $token)
            ->with('success', 'Token API berhasil dibuat (jobs:read, berlaku 30 hari). Simpan token ini, tidak dapat ditampilkan lagi.');
    }

    /**
     * P0 H-08: revoke token yang bocor / tidak dipakai.
     */
    public function destroy(Request $request, string $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();
        $token->delete();

        return redirect()
            ->route('admin.api-tokens.index')
            ->with('success', 'Token API berhasil dicabut.');
    }
}
