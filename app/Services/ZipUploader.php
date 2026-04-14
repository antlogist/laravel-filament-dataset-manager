<?php

namespace App\Services;

use App\Interfaces\ZipUploaderInterface;
use App\Models\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ZipUploader implements ZipUploaderInterface
{
    const STORAGE_DISK = 'local';
    const EXTRACTION_FOLDER = 'dataset';
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
        $folderPath = Storage::disk(self::STORAGE_DISK)->path(self::EXTRACTION_FOLDER);
        $this->extractionPath = $folderPath . DIRECTORY_SEPARATOR . $this->userId . DIRECTORY_SEPARATOR . $timestamp;

        // Unzip files
        FileService::unzipFile($this->realPath, $this->extractionPath);

        // Get folder size
        $this->folderSize = FileService::getFolderSize($this->extractionPath);

        // Calculate files hash
        $this->hash = FileService::getHash(self::EXTRACTION_FOLDER . DIRECTORY_SEPARATOR . $this->userId . DIRECTORY_SEPARATOR . $timestamp, self::STORAGE_DISK);

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
            'hash' => $this->hash
        ]);
    }
}
