<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function view(User $user, Job $job)
    {
        return true; // Anyone can view jobs
    }

    public function create(User $user)
    {
        if ($user->role === 'admin') {
            return true;
        }

        // P0 C-02 + P1 H-09A: hanya perusahaan verified boleh publish.
        // Canonical check via isApproved() (verification_status==='verified').
        if ($user->role === 'company') {
            return (bool) $user->company?->isApproved();
        }

        return false;
    }

    /**
     * P1 H-11: company boleh edit job MILIKNYA sendiri (object-level).
     * Admin via before() di atas. Status TIDAK bisa diubah via update
     * (controller mengabaikan request status) sehingga approval tidak bypass.
     */
    public function update(User $user, Job $job)
    {
        if ($user->role === 'company' && $user->company) {
            return (int) $user->company->id === (int) $job->company_id;
        }

        return false;
    }

    /**
     * P1 H-11/H-12: company boleh menutup & menghapus job miliknya.
     * Hapus diblokir di controller bila sudah ada applications.
     */
    public function delete(User $user, Job $job)
    {
        if ($user->role === 'company' && $user->company) {
            return (int) $user->company->id === (int) $job->company_id;
        }

        return false;
    }

    /**
     * P1 H-11: close = active -> closed, milik sendiri saja.
     */
    public function close(User $user, Job $job)
    {
        if ($user->role === 'company' && $user->company) {
            return (int) $user->company->id === (int) $job->company_id;
        }

        return false;
    }
}
