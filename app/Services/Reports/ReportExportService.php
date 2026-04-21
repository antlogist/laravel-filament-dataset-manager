<?php

namespace App\Services\Reports;

use Illuminate\Support\Collection;

class ReportExportService
{
    public function exportCsv(string $filename, array $headings, Collection $data): void
    {
        // Set answer's headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Write table headings
        fputcsv($output, $headings);

        // Write table body
        $data->each(function ($reportItem) use ($output) {
            fputcsv($output, $reportItem);
        });

        fclose($output);
    }
}
