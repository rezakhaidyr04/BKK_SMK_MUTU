<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Show the force password change view.
     */
    public function forceChange(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        // Jika tidak perlu ganti, redirect ke dashboard
        if (! $request->user()->must_change_password) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.force-password-change');
    }

    /**
     * Update the password and clear the must_change_password flag.
     */
    public function forceUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
            'password_changed_at' => now(),
        ]);

        return redirect()->intended('/dashboard')->with('success', 'Password berhasil diperbarui. Selamat datang!');
    }
}
