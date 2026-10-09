<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Support\Label;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(ReportService $reports)
    {
        $summary = $reports->summary();

        // Monthly applications for chart (last 6 months)
        $months = $reports->monthlyApplications();

        $recentUsers = $reports->recentUsers();
        $recentJobs = $reports->recentJobs();

        // A3: KPI tracer study alumni.
        $tracer = $reports->tracerSummary();
        $recentTracers = $reports->recentTracers();

        return view('admin.reports.index', compact('summary', 'recentUsers', 'recentJobs', 'months', 'tracer', 'recentTracers'));
    }

    public function export(ReportService $reports)
    {
        $summary = $reports->summary();
        $metricRows = $reports->metricRows();
        $months = $reports->monthlyApplications();
        $recentUsers = $reports->recentUsers();
        $recentJobs = $reports->recentJobs();
        $tracer = $reports->tracerSummary();
        $tracerRows = $reports->tracerRows();
        $generatedAt = now()->format('d F Y H:i');

        $rate = $summary['total_applications'] > 0
            ? round(($summary['accepted_applications'] / $summary['total_applications']) * 100, 1)
            : 0;

        $sections = [];

        // Judul + meta
        $sections[] = [['Laporan & Analitik BKKMu']];
        $sections[] = [['Dicetak pada', $generatedAt]];
        $sections[] = [['Tingkat Keberhasilan', $rate.'%']];
        $sections[] = [[]];

        // Ringkasan
        $sections[] = [['RINGKASAN']];
        $sections[] = [['Metrik', 'Nilai']];
        foreach ($metricRows as $row) {
            $sections[] = [$row];
        }
        $sections[] = [[]];

        // Tren 6 bulan
        $sections[] = [['TREN LAMARAN (6 BULAN TERAKHIR)']];
        $sections[] = [['Bulan', 'Jumlah Lamaran']];
        foreach ($months as $month) {
            $sections[] = [[$month['label'], $month['count']]];
        }
        $sections[] = [[]];

        // Pengguna terbaru
        $sections[] = [['PENGGUNA TERBARU']];
        $sections[] = [['Nama', 'Email', 'Role', 'Terdaftar']];
        foreach ($recentUsers as $user) {
            $sections[] = [[
                $user->name,
                $user->email,
                Label::role($user->role),
                optional($user->created_at)->format('d/m/Y'),
            ]];
        }
        $sections[] = [[]];

        // Lowongan terbaru
        $sections[] = [['LOWONGAN TERBARU']];
        $sections[] = [['Judul', 'Perusahaan', 'Lokasi', 'Status', 'Pelamar', 'Dibuat']];
        foreach ($recentJobs as $job) {
            $sections[] = [[
                $job->title,
                $job->company_name ?? '-',
                $job->location ?? '-',
                Label::jobStatus($job->status),
                $job->applications_count ?? 0,
                optional($job->created_at)->format('d/m/Y'),
            ]];
        }
        $sections[] = [[]];

        // A3: Tracer study alumni.
        $sections[] = [['TRACER STUDY ALUMNI']];
        $sections[] = [['Metrik', 'Nilai']];
        foreach ($tracerRows as $row) {
            $sections[] = [$row];
        }

        $filename = 'laporan-bkk-'.now()->format('YmdHis').'.csv';
        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM agar rapi dibuka di Excel Indonesia

        foreach ($sections as $section) {
            $row = $section[0];
            if ($row === []) {
                $csv .= "\r\n";

                continue;
            }
            $escaped = array_map(function ($value) {
                $value = (string) ($value ?? '');
                $value = str_replace('"', '""', $value);

                return '"'.$value.'"';
            }, $row);
            $csv .= implode(',', $escaped)."\r\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf(ReportService $reports)
    {
        $summary = $reports->summary();
        $rows = $reports->metricRows();
        $months = $reports->monthlyApplications();
        $recentUsers = $reports->recentUsers();
        $recentJobs = $reports->recentJobs();
        $tracer = $reports->tracerSummary();
        $tracerRows = $reports->tracerRows();
        $recentTracers = $reports->recentTracers();
        $generatedAt = now()->format('d F Y H:i');

        $rate = $summary['total_applications'] > 0
            ? round(($summary['accepted_applications'] / $summary['total_applications']) * 100, 1)
            : 0;

        $filename = 'laporan-bkk-'.now()->format('YmdHis').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'summary' => $summary,
            'rows' => $rows,
            'months' => $months,
            'recentUsers' => $recentUsers,
            'recentJobs' => $recentJobs,
            'tracer' => $tracer,
            'tracerRows' => $tracerRows,
            'recentTracers' => $recentTracers,
            'rate' => $rate,
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
