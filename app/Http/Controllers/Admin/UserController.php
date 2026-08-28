<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserStoreRequest;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

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
            ->orderByDesc("created_at")
            ->paginate(15)
            ->withQueryString();

        return view("admin.users.index", compact("users"));
    }

    public function show(User $user)
    {
        $user->load(
            "applications.job",
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

    public function store(AdminUserStoreRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $validated["role"],
            "password" => bcrypt($validated["password"]),
            "is_active" => $validated["is_active"] ?? true,
            "email_verified_at" => in_array($validated["role"], ["admin", "company"])
                ? now()
                : null,
        ]);

        $user->syncRoles([$validated['role']]);

        // Jika role company, buat record Company otomatis
        if ($validated["role"] === "company") {
            Company::create([
                "user_id"             => $user->id,
                "name"                => $validated["name"],
                "is_verified"         => false,
                "verification_status" => "pending",
            ]);
        }

        return redirect()
            ->route("admin.users.index")
            ->with("success", "Pengguna baru berhasil dibuat.");
    }

    public function update(AdminUserUpdateRequest $request, User $user)
    {
        $validated = $request->validated();

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

        if ($validated["role"] === "admin") {
            $user->email_verified_at ??= now();
        }

        if (!empty($validated["password"])) {
            $user->password = bcrypt($validated["password"]);
        }

        $user->save();

        if (isset($validated['role'])) {
            $user->syncRoles([$user->role]);
        }

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
