<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index()
    {
        return view('admin.api-tokens.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => ['nullable', 'string', 'max:255'],
        ]);

        $name = $request->input('token_name') ?: 'admin-token-' . now()->format('YmdHis');
        $token = $request->user()->createToken($name)->plainTextToken;

        return redirect()
            ->route('admin.api-tokens.index')
            ->with('api_token', $token)
            ->with('success', 'Token API berhasil dibuat. Simpan token ini, tidak dapat ditampilkan lagi.');
    }
}
