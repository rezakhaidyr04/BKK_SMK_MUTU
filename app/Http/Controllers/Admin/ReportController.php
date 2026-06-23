<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_students' => User::where('role', 'student')->count(),
            'total_alumni' => User::where('role', 'alumni')->count(),
            'total_companies' => Company::count(),
            'total_jobs' => Job::count(),
            'active_jobs' => Job::where('status', 'active')->count(),
            'total_applications' => Application::count(),
            'submitted_applications' => Application::where('status', 'submitted')->count(),
            'accepted_applications' => Application::where('status', 'accepted')->count(),
        ];

        $recentUsers = User::latest()->take(8)->get();
        $recentCompanies = Company::latest()->take(8)->get();
        $recentJobs = Job::latest()->take(8)->get();

        return view('admin.reports.index', compact('summary', 'recentUsers', 'recentCompanies', 'recentJobs'));
    }

    public function export()
    {
        $rows = [
            ['Metrik', 'Nilai'],
            ['Total Siswa', User::where('role', 'student')->count()],
            ['Total Alumni', User::where('role', 'alumni')->count()],
            ['Total Perusahaan', Company::count()],
            ['Total Lowongan', Job::count()],
            ['Lowongan Aktif', Job::where('status', 'active')->count()],
            ['Total Lamaran', Application::count()],
            ['Lamaran Terkirim', Application::where('status', 'submitted')->count()],
            ['Lamaran Diterima', Application::where('status', 'accepted')->count()],
        ];

        $filename = 'admin-report-' . now()->format('YmdHis') . '.csv';
        $csv = '';

        foreach ($rows as $row) {
            $escaped = array_map(function ($value) {
                $value = (string) $value;
                $value = str_replace('"', '""', $value);
                return '"' . $value . '"';
            }, $row);

            $csv .= implode(',', $escaped) . "\r\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
