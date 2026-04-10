<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ZipUploader
{
    const EXTRACTION_PATH = 'public' . DIRECTORY_SEPARATOR . 'dataset';
    const FILE_EXT = ['jpg', 'png'];

    public $userId;
    public $realPath = '';
    public $zipSize = 0;
    public $folderSize = 0;
    public $numberOfFiles = 0;
    public $hash = '';

    public function save(TemporaryUploadedFile $file)
    {
        try {
            $this->userId = Auth::id();
            $this->realPath = $file->getRealPath();
            $this->zipSize = $file->getSize(); //Bytes

            // Check the archive for allowed files
            FileService::checkZipFileExtensions($this->realPath, self::FILE_EXT);

            // Unzip and save
            $this->proccessUploadedZip();
        } catch (\Exception $e) {
            $errorMessage = 'Error when uploading a file: ' . $e->getMessage();
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }
    }

    private function proccessUploadedZip()
    {
        // Unique folder for unzip files
        $timestamp = date('Y-m-d-H-i-s');
        $extractionPath = 'app' . DIRECTORY_SEPARATOR . self::EXTRACTION_PATH . DIRECTORY_SEPARATOR . $this->userId . DIRECTORY_SEPARATOR . $timestamp;

        // Unzip files
        FileService::unzipFile($this->realPath, $extractionPath);

        // Archive deletion
        FileService::deleteFile($this->realPath);
    }
}
