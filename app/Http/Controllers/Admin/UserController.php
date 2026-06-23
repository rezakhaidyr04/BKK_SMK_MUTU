<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("email", "like", "%{$search}%")
                    ->orWhere("role", "like", "%{$search}%");
            });
        }

        if ($request->filled("role")) {
            $query->where("role", $request->role);
        }

        if ($request->filled("status")) {
            $query->where("is_active", $request->status === "active");
        }

        $users = $query
            ->with("company")
            ->orderByDesc("created_at")
            ->paginate(15)
            ->withQueryString();

        return view("admin.users.index", compact("users"));
    }

    public function show(User $user)
    {
        $user->load(
            "company",
            "applications.job.company",
            "certificates",
            "cvFiles",
        );

        return view("admin.users.show", compact("user"));
    }

    public function edit(User $user)
    {
        return view("admin.users.edit", compact("user"));
    }

    public function create()
    {
        return view("admin.users.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255", "unique:users,email"],
            "role" => [
                "required",
                Rule::in(["admin", "company", "student", "alumni", "teacher"]),
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "password_confirmation" => ["required"],
            "is_active" => ["nullable", "boolean"],
        ]);

        User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $validated["role"],
            "password" => bcrypt($validated["password"]),
            "is_active" => $validated["is_active"] ?? true,
        ]);

        return redirect()
            ->route("admin.users.index")
            ->with("success", "Pengguna baru berhasil dibuat.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "email",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],
            "role" => [
                "required",
                Rule::in(["admin", "company", "student", "alumni", "teacher"]),
            ],
            "is_active" => ["nullable", "boolean"],
            "password" => ["nullable", "string", "min:8"],
        ]);

        if ($user->id === auth()->id() && $validated["role"] !== $user->role) {
            return back()->with(
                "error",
                "Anda tidak dapat mengubah peran akun sendiri.",
            );
        }

        $user->fill([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $validated["role"],
            "is_active" => $validated["is_active"] ?? false,
        ]);

        if (!empty($validated["password"])) {
            $user->password = bcrypt($validated["password"]);
        }

        $user->save();

        return redirect()
            ->route("admin.users.index")
            ->with("success", "Pengguna berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with(
                "error",
                "Anda tidak dapat menghapus akun sendiri.",
            );
        }

        $user->delete();

        return redirect()
            ->route("admin.users.index")
            ->with("success", "Pengguna berhasil dihapus.");
    }
}
