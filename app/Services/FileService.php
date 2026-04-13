<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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

    public static function unzipFile(string $realPath, string $targetPath): void
    {
        try {
            $zip = new ZipArchive();
            if ($zip->open($realPath) !== true) {
                throw new Exception("Error opening the archive.");
            }

            $isUnzipped = $zip->extractTo(storage_path($targetPath));
            $zip->close();

            if (!$isUnzipped) {
                throw new Exception("Archive unpacking error.");
            }
        } catch (\Exception $e) {
            $errorMessage = "The archive has not been unpacked.";
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage . ' ' . $e->getMessage());

            Log::error($logMessage);
            throw new \Exception($errorMessage);
        }
    }

    public static function deleteFile(string $path): void
    {
        try {
            if (file_exists($path)) {
                // Deletion
                if (!unlink($path)) {
                    throw new \Exception('File deletion error: ' . $path);
                }
            } else {
                throw new \Exception('The file was not found when it was deleted: ' . $path);
            }
        } catch (\Exception $e) {
            $logMessage = sprintf("[%s] %s", __METHOD__, $e->getMessage());

            Log::error($logMessage);
            throw new \Exception($e->getMessage());
        }
    }

    public static function getFolderSize(string $path): int
    {
        $fullPath = storage_path($path);
        dd($fullPath);

        if (!is_dir($fullPath)) {
            $errorMessage = 'The folder was not found when calculating its size';

            info($errorMessage . ' :' . $fullPath);

            throw new \Exception($errorMessage);
        }

        return self::calculateFolderSize(storage_path($path));
    }

    public static function calculateFolderSize(?string $path): int
    {
        if (!$path) {
            return 0;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $size = 0;
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}
