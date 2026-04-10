<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class FileService
{
    public static function checkZipFileExtensions(string $zipFilePath, array $allowedExtensions): bool
    {
        // Checking the existence of a file
        if (!file_exists($zipFilePath)) {
            $errorMessage = 'File not found ' . $zipFilePath;
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }

        // Open archive
        $zip = new ZipArchive();

        if ($zip->open($zipFilePath) !== true) {
            $errorMessage = 'The archive does not open. The archive may be corrupted.';
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }

        // Transform allowed extensions into lowercase
        $allowedExtensions = array_map('strtolower', $allowedExtensions);

        // Checking file extensions inside the archive
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $filename = $stat['name'];

            if (substr($filename, -1) === '/') {
                continue;
            }

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExtensions)) {
                $errorMessage = 'An attempt to download an archive containing a file with an invalid extension ' . $ext;
                $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

                Log::info($logMessage);
                throw new \Exception($errorMessage);
            }
        }

        return true;
    }
}
