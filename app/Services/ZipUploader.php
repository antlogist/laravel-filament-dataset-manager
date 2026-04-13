<?php

namespace App\Services;

use App\Models\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ZipUploader
{
    const EXTRACTION_PATH = 'public' . DIRECTORY_SEPARATOR . 'dataset';
    const FILE_EXT = ['jpg', 'png'];

    public int $userId;
    public int $hostId;
    public string $name;
    public string $realPath;
    public string $extractionPath;
    public int $zipSize = 0;
    public int $folderSize = 0;
    public int $numberOfFiles = 0;
    public string $hash = '';

    public function save(TemporaryUploadedFile $file, int $hostId, string $name)
    {
        try {
            $this->userId = Auth::id();
            $this->realPath = $file->getRealPath();
            $this->zipSize = $file->getSize(); //Bytes
            $this->name = $name;
            $this->hostId = $hostId;

            // Check the archive for allowed files
            FileService::checkZipFileExtensions($this->realPath, self::FILE_EXT);

            // Unzip and save
            $this->proccessUploadedZip();

            $this->persistToDatabase();
        } catch (\Exception $e) {
            $errorMessage = 'Error when uploading a file: ' . $e->getMessage();
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            // FileService::deleteFile($this->realPath);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }
    }

    private function proccessUploadedZip()
    {
        // Unique folder for unzip files
        $timestamp = date('Y-m-d-H-i-s');
        $this->extractionPath = 'app' . DIRECTORY_SEPARATOR . self::EXTRACTION_PATH . DIRECTORY_SEPARATOR . $this->userId . DIRECTORY_SEPARATOR . $timestamp;

        // Unzip files
        FileService::unzipFile($this->realPath, $this->extractionPath);

        $this->folderSize = FileService::getFolderSize($this->extractionPath);

        // Archive deletion
        FileService::deleteFile($this->realPath);
    }

    private function persistToDatabase()
    {
        UploadedFile::create([
            'user_id' => $this->userId,
            'host_id' => $this->hostId,
            'source_path' => $this->extractionPath,
            'name' => $this->name,
            'size_bytes' => $this->folderSize,
            'zip_size_bytes' => $this->zipSize,
            'number_of_file' => $this->numberOfFiles,
            'dataset_type' => 'image',
            'hash' => 'test_hash'
        ]);
    }
}
