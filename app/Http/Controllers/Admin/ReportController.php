<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
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

        return view('admin.reports.index', compact('summary', 'recentUsers', 'recentJobs', 'months'));
    }

    public function export(ReportService $reports)
    {
        $rows = array_merge(
            [['Metrik', 'Nilai']],
            $reports->metricRows()
        );

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

    public function exportExcel(ReportService $reports)
    {
        $filename = 'laporan-bkk-' . now()->format('YmdHis') . '.xls';

        $rows = array_merge(
            [['Metrik', 'Nilai']],
            $reports->metricRows()
        );

        // Real Excel 2003 XML (SpreadsheetML) — opens natively in Excel
        $xml = '<?xml version="1.0"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
        $xml .= '  <Worksheet ss:Name="Laporan BKK">' . "\n";
        $xml .= '    <Table>' . "\n";

        foreach ($rows as $row) {
            $xml .= '      <Row>' . "\n";
            foreach ($row as $cell) {
                $type = is_numeric($cell) ? 'Number' : 'String';
                $value = htmlspecialchars((string) $cell, ENT_XML1);
                $xml .= '        <Cell><Data ss:Type="' . $type . '">' . $value . '</Data></Cell>' . "\n";
            }
            $xml .= '      </Row>' . "\n";
        }

        $xml .= '    </Table>' . "\n";
        $xml .= '  </Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf(ReportService $reports)
    {
        $rows = $reports->metricRows();

        $filename = 'laporan-bkk-' . now()->format('YmdHis') . '.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'rows' => $rows,
            'generatedAt' => now()->format('d F Y H:i'),
        ]);

        return $pdf->download($filename);
    }
}
