<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Halaman Pengaturan Admin (terpisah dari halaman profil Pencari Kerja).
 * Admin boleh mengganti foto profil, nama, email, nomor HP, dan kata sandinya.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $admin = $request->user();

        $stats = [
            'users' => User::where('role', 'umum')->count(),
            'companies' => Company::count(),
            'jobs' => Job::count(),
            'pendingJobReports' => JobReport::where('status', 'menunggu')->count(),
        ];

        return view('admin.profile.edit', compact('admin', 'stats'));
    }
}
