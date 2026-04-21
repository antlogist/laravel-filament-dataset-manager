<?php

namespace App\Services\Reports;

use App\Models\UploadedFile;
use App\Services\Reports\ReportExportService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UploadedFilesReport
{
    public static function report()
    {
        $exportService = new ReportExportService();

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $fileName = "uploaded_files_report_{$timestamp}.csv";
        $tableHeadings = self::getReportHeadings();
        $tableBody = self::getReportBody();

        $exportService->exportCsv($fileName, $tableHeadings, $tableBody);
    }

    private static function getReportHeadings(): array
    {
        return ['Host', 'Source', 'Name', 'Email'];
    }

    private static function getReportBody(): Collection
    {
        $reportData = UploadedFile::all();

        $preparedData = $reportData->map(function ($reportItem) {
            return [
                'host' => $reportItem['host']['name'],
                'source_path' => $reportItem['source_path'],
                'user' => $reportItem['user']['name'],
                'email' => $reportItem['user']['email'],
            ];
        });

        return $preparedData;
    }
}
