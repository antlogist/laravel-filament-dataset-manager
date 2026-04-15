<?php

namespace App\Jobs;

use App\Services\QueuedZipUploader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ZipUploaderJob implements ShouldQueue
{
    use Queueable;

    public $dataForJob;

    /**
     * Create a new job instance.
     */
    public function __construct(object $dataForJob)
    {
        $this->dataForJob = $dataForJob;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $uploader = resolve(QueuedZipUploader::class);

            $uploader->proccessUploadedZip($this->dataForJob->userId, $this->dataForJob->realPath);

            $uploader->persistToDatabase($this->dataForJob->userId, $this->dataForJob->hostId, $this->dataForJob->name, $this->dataForJob->zipSize);
            
        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while unpacking the archive.';

            Log::info($errorMessage . ': ' . $e->getMessage());

            throw new \Exception($errorMessage);
        }
    }
}
