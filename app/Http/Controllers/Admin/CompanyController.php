<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // LIST
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Company::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $companies    = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $pendingCount = Company::where('verification_status', 'pending')->count();

        return view('admin.companies.index', compact('companies', 'pendingCount'));
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE — Admin membuat data perusahaan baru
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.companies.create');
    }

    // ─────────────────────────────────────────────────────────────
    // STORE — Simpan perusahaan baru (belum ada akun user)
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'industry'        => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'website'         => ['nullable', 'url', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone'           => ['nullable', 'string', 'max:50'],
            'address'         => ['nullable', 'string', 'max:500'],
            'tax_number'      => ['nullable', 'string', 'max:100'],
            'mou_path'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'mou_number'      => ['nullable', 'string', 'max:255'],
            'mou_signed_at'   => ['nullable', 'date'],
            'mou_expires_at'  => ['nullable', 'date', 'after_or_equal:mou_signed_at'],
        ]);

        // Handle MoU file upload (private disk)
        $mouPath = null;
        if ($request->hasFile('mou_path')) {
            $mouPath = $request->file('mou_path')->store(
                'company_mou',
                'local'          // private — tidak dapat diakses via URL langsung
            );
        }

        Company::create([
            'user_id'             => null,   // akun belum dibuat
            'name'                => $validated['name'],
            'industry'            => $validated['industry'] ?? null,
            'description'         => $validated['description'] ?? null,
            'website'             => $validated['website'] ?? null,
            'email'               => $validated['email'] ?? null,
            'phone'               => $validated['phone'] ?? null,
            'address'             => $validated['address'] ?? null,
            'tax_number'          => $validated['tax_number'] ?? null,
            'mou_path'            => $mouPath,
            'mou_number'          => $validated['mou_number'] ?? null,
            'mou_signed_at'       => $validated['mou_signed_at'] ?? null,
            'mou_expires_at'      => $validated['mou_expires_at'] ?? null,
            'is_verified'         => false,
            'verification_status' => 'pending',
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Data perusahaan berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────
    public function show(Company $company)
    {
        $company->load(['user', 'jobs', 'reviewer']);

        return view('admin.companies.show', compact('company'));
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'industry'       => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'website'        => ['nullable', 'url', 'max:255'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'address'        => ['nullable', 'string', 'max:500'],
            'tax_number'     => ['nullable', 'string', 'max:100'],
            'mou_path'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'mou_number'     => ['nullable', 'string', 'max:255'],
            'mou_signed_at'  => ['nullable', 'date'],
            'mou_expires_at' => ['nullable', 'date', 'after_or_equal:mou_signed_at'],
            'is_verified'    => ['nullable', 'boolean'],
        ]);

        // Handle MoU file upload
        if ($request->hasFile('mou_path')) {
            // Hapus file MoU lama jika ada
            if ($company->mou_path && Storage::disk('local')->exists($company->mou_path)) {
                Storage::disk('local')->delete($company->mou_path);
            }
            $validated['mou_path'] = $request->file('mou_path')->store('company_mou', 'local');
        } else {
            unset($validated['mou_path']); // Jangan overwrite jika tidak upload baru
        }

        $isVerified = (bool) ($validated['is_verified'] ?? false);

        $company->update(array_merge($validated, [
            'is_verified'         => $isVerified,
            'verification_status' => $isVerified ? 'verified' : $company->verification_status,
        ]));

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────────
    // APPROVE
    // ─────────────────────────────────────────────────────────────
    public function approve(Company $company)
    {
        $company->update([
            'is_verified'         => true,
            'verification_status' => 'verified',
            'rejection_reason'    => null,
            'reviewed_by'         => auth()->id(),
            'reviewed_at'         => now(),
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', "Perusahaan \"{$company->name}\" berhasil diverifikasi.");
    }

    // ─────────────────────────────────────────────────────────────
    // REJECT — wajib ada rejection_reason
    // ─────────────────────────────────────────────────────────────
    public function reject(Request $request, Company $company)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $company->update([
            'is_verified'         => false,
            'verification_status' => 'rejected',
            'rejection_reason'    => $validated['rejection_reason'],
            'reviewed_by'         => auth()->id(),
            'reviewed_at'         => now(),
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', "Verifikasi perusahaan \"{$company->name}\" ditolak.");
    }

    // ─────────────────────────────────────────────────────────────
    // MOU DOWNLOAD — private, hanya admin yang dapat mengakses
    // ─────────────────────────────────────────────────────────────
    public function downloadMou(Company $company)
    {
        $this->authorize('downloadMou', $company);

        if (! $company->mou_path) {
            abort(404, 'File MoU tidak ditemukan.');
        }

        if (! Storage::disk('local')->exists($company->mou_path)) {
            abort(404, 'File MoU tidak ditemukan di storage.');
        }

        $fileName = 'MoU_' . Str::slug($company->name) . '_' .
                    ($company->mou_number ? Str::slug($company->mou_number) . '_' : '') .
                    now()->format('Ymd') . '.' .
                    pathinfo($company->mou_path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download($company->mou_path, $fileName);
    }

    public function downloadLegalDocument(Company $company, string $document)
    {
        $this->authorize('downloadLegalDocument', $company);

        $column = match ($document) {
            'business-license' => 'business_license_path',
            'operating-license' => 'operating_license_path',
            'npwp' => 'npwp_path',
            default => abort(404),
        };

        $path = $company->{$column};
        abort_unless($path, 404);

        abort_unless(Storage::disk('private')->exists($path), 404);

        return Storage::disk('private')->download($path);
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE ACCOUNT — hanya untuk perusahaan APPROVED & belum punya akun
    // ─────────────────────────────────────────────────────────────
    public function createAccount(Request $request, Company $company)
    {
        // 1. Hanya perusahaan yang SUDAH APPROVED yang boleh dibuatkan akun
        if (! $company->isApproved()) {
            return redirect()
                ->route('admin.companies.show', $company)
                ->with('error', 'Akun hanya dapat dibuat untuk perusahaan yang sudah disetujui (approved).');
        }

        // 2. Cegah duplikat: jika sudah punya akun
        if ($company->hasUserAccount()) {
            return redirect()
                ->route('admin.companies.show', $company)
                ->with('error', 'Perusahaan ini sudah memiliki akun login.');
        }

        // 3. Tentukan email: prioritaskan companies.email, lalu minta admin input
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',   // Email harus unik di tabel users
            ],
        ], [
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
            'email.required' => 'Email login diperlukan untuk membuat akun.',
        ]);

        // 4. Generate password sementara yang aman (12 karakter)
        $plainPassword = Str::password(12, letters: true, numbers: true, symbols: false);

        // 5. Buat user — simpan password HANYA dalam bentuk hash
        // must_change_password = true paksa company login pertama kali
        // dan mengarahkan ke halaman ganti password via ForcePasswordChange middleware.
        $user = User::create([
            'name'                => $company->name,
            'email'               => $validated['email'],
            'password'            => Hash::make($plainPassword),   // NEVER store plaintext
            'role'                => 'company',
            'is_active'           => true,
            'email_verified_at'   => now(),
            'must_change_password' => true,
        ]);

        // 6. Sync Spatie role = 'company'
        $user->syncRoles(['company']);

        // 7. Hubungkan user ke perusahaan
        $company->update(['user_id' => $user->id]);

        // 8. Password awal akan ditampilkan SATU KALI di halaman berikutnya
        // menggunakan session Laravel — akan otomatis hilang setelah ditampilkan.
        return redirect()
            ->route('admin.companies.show', $company)
            ->with('account_created', true)
            ->with('account_email', $validated['email'])
            ->with('initial_password', $plainPassword)
            ->with('success', "Akun berhasil dibuat untuk {$company->name}. Password awal telah dibuat. <strong>Disarankan</strong> mengganti password setelah login pertama untuk keamanan maksimal.");
    }
}
