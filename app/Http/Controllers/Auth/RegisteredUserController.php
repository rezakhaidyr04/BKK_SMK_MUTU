<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view("auth.register");
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
            "role" => ["nullable", "string", "in:student,alumni,company"],
            "nis" => ["nullable", "string", "max:20", "unique:students,nisn"],
            "graduation_year" => [
                "nullable",
                "integer",
                "min:2000",
                "max:" . (date("Y") + 5),
            ],
            // 'terms' => ['required', 'accepted'], // Tidak dipakai di form registrasi saat ini
        ]);

        $role = $request->input("role", "student");

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $role,
            "is_active" => true,
        ]);

        // Create student record if role is student or alumni
        if (in_array($role, ["student", "alumni"])) {
            $user->student()->create([
                "nisn" => $role === "student" ? $request->nis : null,
                "graduation_year" => $request->graduation_year,
            ]);
        }

        // Create company record if role is company
        if ($role === "company") {
            $user->company()->create([
                "name" => $request->name,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
