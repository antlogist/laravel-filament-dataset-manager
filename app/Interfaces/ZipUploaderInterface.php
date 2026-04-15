<?php

namespace App\Interfaces;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

interface ZipUploaderInterface
{
    public function save(TemporaryUploadedFile $file, int $hostId, string $name);
}
