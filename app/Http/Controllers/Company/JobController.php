<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyJobStoreRequest;
use App\Http\Requests\CompanyJobUpdateRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        $query = Job::where('company_id', $company?->id);

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('position', 'like', '%'.$request->search.'%')
                    ->orWhere('location', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->withCount('applications')->latest()->paginate(10)->withQueryString();

        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        if (\Illuminate\Support\Facades\Gate::denies('create', Job::class)) {
            return redirect()->route('company.profile.edit')->with('error', 'Akun perusahaan Anda belum diverifikasi oleh admin. Silakan lengkapi profil dan ajukan verifikasi.');
        }

        return view('company.jobs.create');
    }

    public function store(CompanyJobStoreRequest $request)
    {
        if (Gate::denies('create', Job::class)) {
            return redirect()->route('company.jobs.index')->with(
                'error',
                'Akun perusahaan Anda belum diverifikasi oleh admin. Tidak dapat mempublikasikan lowongan saat ini.',
            );
        }

        // P1 H-09B: company_id/company_name TIDAK diterima dari request
        // (cegah spoofing milik company lain). Keduanya diisi server-side
        // dari authenticated company di bawah.
        $validated = $request->validated();

        // P1 H-09B: ownership selalu dari authenticated company.
        $company = $request->user()->company;
        $validated['company_id'] = $company->id;
        $validated['company_name'] = $company->name;
        $validated['status'] = 'pending';

        Job::create($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil dibuat dan menunggu persetujuan admin.');
    }

    /**
     * P1 H-11: form edit — hanya pemilik (policy update). Direct URL aman.
     */
    public function edit(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        return view('company.jobs.edit', compact('job'));
    }

    /**
     * P1 H-11: update — hanya field konten. company_id/company_name/status
     * TIDAK diterima dari request (identity + workflow dilindungi).
     * Status tidak berubah (pending tetap pending, active tetap active)
     * sehingga tidak bypass approval admin.
     */
    public function update(CompanyJobUpdateRequest $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validated();

        $job->update($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * P1 H-11: close — active -> closed. Setelah closed, C-03 menolak
     * lamaran baru. History applications TIDAK dihapus.
     */
    public function close(Request $request, Job $job)
    {
        $this->authorize('close', $job);

        if ($job->status === 'closed') {
            return back()->with('info', 'Lowongan sudah dalam keadaan ditutup.');
        }

        if ($job->status !== 'active') {
            return back()->with('error', 'Hanya lowongan aktif yang dapat ditutup.');
        }

        $job->update(['status' => 'closed']);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil ditutup. Lamaran baru tidak lagi diterima.');
    }

    /**
     * P1 H-12: delete diblokir bila sudah ada applications (lindungi history).
     * Tanpa applications: soft delete (Job memakai SoftDeletes; FK cascade
     * hanya berjalan saat forceDelete, sehingga aman).
     */
    public function destroy(Request $request, Job $job)
    {
        $this->authorize('delete', $job);

        if ($job->applications()->exists()) {
            return back()->with('error', 'Lowongan tidak dapat dihapus karena sudah memiliki data lamaran. Gunakan Tutup Lowongan agar history tetap aman.');
        }

        $job->delete();

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
