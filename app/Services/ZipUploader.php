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

    public int $hostId;
    public string $name;
    public string $extractionPath;
    public int $zipSize = 0;
    public int $folderSize = 0;
    public int $numberOfFiles = 0;
    public string $hash = '';

    public function save(TemporaryUploadedFile $file, int $hostId, string $name)
    {
        try {
            $userId = Auth::id();
            $realPath = $file->getRealPath();
            $zipSize = $file->getSize(); //Bytes

            // Check the archive for allowed files
            FileService::checkZipFileExtensions($realPath, self::FILE_EXT);

            // Unzip and save
            $this->proccessUploadedZip($userId, $realPath);

            $this->persistToDatabase($userId, $hostId, $name, $zipSize);
        } catch (\Exception $e) {
            $errorMessage = 'Error when uploading a file: ' . $e->getMessage();
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            // FileService::deleteFile($realPath);

            Log::info($logMessage);
            throw new \Exception($errorMessage);
        }
    }

    public function proccessUploadedZip(int $userId, string $realPath)
    {
        // Unique folder for unzip files
        $timestamp = date('Y-m-d-H-i-s');
        $folderPath = Storage::disk(self::STORAGE_DISK)->path(self::EXTRACTION_FOLDER);
        $this->extractionPath = $folderPath . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $timestamp;

        // Unzip files
        FileService::unzipFile($realPath, $this->extractionPath);

        // Get folder size
        $this->folderSize = FileService::getFolderSize($this->extractionPath);

        // Calculate files hash
        $this->hash = FileService::getHash(self::EXTRACTION_FOLDER . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $timestamp, self::STORAGE_DISK);

        // Archive deletion
        FileService::deleteFile($realPath);
    }

    public function persistToDatabase($userId, $hostId, $name, $zipSize)
    {
        UploadedFile::create([
            'user_id' => $userId,
            'host_id' => $hostId,
            'source_path' => $this->extractionPath,
            'name' => $name,
            'size_bytes' => $this->folderSize,
            'zip_size_bytes' => $zipSize,
            'number_of_file' => $this->numberOfFiles,
            'dataset_type' => 'image',
            'hash' => $this->hash
        ]);
    }
}
