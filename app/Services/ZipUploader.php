<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ZipUploader
{
    const EXTRACTION_PATH = 'public' . DIRECTORY_SEPARATOR . 'dataset';
    const FILE_EXT = ['jpg', 'png'];

    public $realPath = '';
    public $zipSize = 0;
    public $folderSize = 0;
    public $numberOfFiles = 0;
    public $hash = '';

    public function save(TemporaryUploadedFile $file)
    {
        try {
            $this->realPath = $file->getRealPath();

            // Check the archive for allowed files
            FileService::checkZipFileExtensions($this->realPath, self::FILE_EXT);
        } catch (\Exception $e) {
            $errorMessage = 'Error when uploading a file: ' . $e->getMessage();
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }
    }
}
