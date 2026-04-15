<?php

namespace App\Services;

use App\Interfaces\ZipUploaderInterface;
use App\Jobs\ZipUploaderJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class QueuedZipUploader extends ZipUploader implements ZipUploaderInterface
{
    public function save(TemporaryUploadedFile $file, int $hostId, string $name)
    {
        try {
            $userId = Auth::id();
            $realPath = $file->getRealPath();
            $zipSize = $file->getSize(); //Bytes
            $name = $name;
            $hostId = $hostId;

            // Check the archive for allowed files
            FileService::checkZipFileExtensions($realPath, self::FILE_EXT);
        } catch (\Exception $e) {
            $errorMessage = 'Error when uploading a file: ' . $e->getMessage();
            $logMessage = sprintf("[%s] %s", __METHOD__, $errorMessage);

            // FileService::deleteFile($realPath);

            Log::info($logMessage);

            throw new \Exception($errorMessage);
        }

        $dataForJob = (object)[
            'userId' => $userId,
            'realPath' => $realPath,
            'hostId' => $hostId,
            'name' => $name,
            'zipSize' => $zipSize
        ];

        ZipUploaderJob::dispatch($dataForJob);
    }
}
