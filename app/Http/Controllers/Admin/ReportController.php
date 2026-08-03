<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_students'          => User::where('role', 'student')->count(),
            'total_alumni'            => User::where('role', 'alumni')->count(),
            'total_jobs'              => Job::count(),
            'active_jobs'             => Job::where('status', 'active')->count(),
            'closed_jobs'             => Job::where('status', 'closed')->count(),
            'total_applications'      => Application::count(),
            'submitted_applications'  => Application::where('status', 'submitted')->count(),
            'accepted_applications'   => Application::where('status', 'accepted')->count(),
            'rejected_applications'   => Application::where('status', 'rejected')->count(),
            'interviewed_applications'=> Application::where('status', 'interviewed')->count(),
        ];

        // Monthly applications for chart (last 6 months)
        $months = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'label' => $date->format('M Y'),
                'count' => Application::whereYear('created_at', $date->year)
                                      ->whereMonth('created_at', $date->month)
                                      ->count(),
            ];
        });

        $recentUsers = User::latest()->take(6)->get();
        $recentJobs  = Job::withCount('applications')->latest()->take(6)->get();

        return view('admin.reports.index', compact('summary', 'recentUsers', 'recentJobs', 'months'));
    }

    public function export()
    {
        $rows = [
            ['Metrik', 'Nilai'],
            ['Total Siswa',                 User::where('role', 'student')->count()],
            ['Total Alumni',                User::where('role', 'alumni')->count()],
            ['Total Lowongan',              Job::count()],
            ['Lowongan Aktif',              Job::where('status', 'active')->count()],
            ['Lowongan Ditutup',            Job::where('status', 'closed')->count()],
            ['Total Lamaran',               Application::count()],
            ['Lamaran Diajukan',            Application::where('status', 'submitted')->count()],
            ['Lamaran Diterima',            Application::where('status', 'accepted')->count()],
            ['Lamaran Ditolak',             Application::where('status', 'rejected')->count()],
            ['Lamaran Diwawancara',         Application::where('status', 'interviewed')->count()],
        ];

        $filename = 'laporan-bkk-' . now()->format('YmdHis') . '.csv';
        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM

        foreach ($rows as $row) {
            $escaped = array_map(function ($value) {
                $value = (string) $value;
                $value = str_replace('"', '""', $value);
                return '"' . $value . '"';
            }, $row);
            $csv .= implode(',', $escaped) . "\r\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportExcel()
    {
        $filename = 'laporan-bkk-' . now()->format('YmdHis') . '.xls';

        $rows = [
            ['Metrik', 'Nilai'],
            ['Total Siswa',                 User::where('role', 'student')->count()],
            ['Total Alumni',                User::where('role', 'alumni')->count()],
            ['Total Lowongan',              Job::count()],
            ['Lowongan Aktif',              Job::where('status', 'active')->count()],
            ['Lowongan Ditutup',            Job::where('status', 'closed')->count()],
            ['Total Lamaran',               Application::count()],
            ['Lamaran Diajukan',            Application::where('status', 'submitted')->count()],
            ['Lamaran Diterima',            Application::where('status', 'accepted')->count()],
            ['Lamaran Ditolak',             Application::where('status', 'rejected')->count()],
            ['Lamaran Diwawancara',         Application::where('status', 'interviewed')->count()],
        ];

        // Build HTML table — browsers recognize this as Excel-compatible XLS
        $html  = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $html .= 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
        $html .= 'xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><meta name=ProgId content=Excel.Sheet>';
        $html .= '<style>body{font-family:Arial,sans-serif;font-size:11pt;}';
        $html .= 'th{background:#2563eb;color:#fff;padding:8px 12px;text-align:left;font-weight:bold;}';
        $html .= 'td{padding:8px 12px;border-bottom:1px solid #e2e8f0;}';
        $html .= 'tr:nth-child(even) td{background:#f8fafc;}';
        $html .= '</style></head><body>';
        $html .= '<h2 style="font-family:Arial;color:#1e3a8a;">Laporan BKK SMK MUTU — ' . now()->format('d M Y') . '</h2>';
        $html .= '<table border="1" cellspacing="0">';

        foreach ($rows as $i => $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $tag   = ($i === 0) ? 'th' : 'td';
                $html .= "<{$tag}>" . htmlspecialchars((string) $cell) . "</{$tag}>";
            }
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
