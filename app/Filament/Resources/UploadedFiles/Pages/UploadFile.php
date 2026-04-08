<?php

namespace App\Filament\Resources\UploadedFiles\Pages;

use App\Filament\Resources\UploadedFiles\UploadedFileResource;
use Filament\Resources\Pages\Page;

class UploadFile extends Page
{
    protected static string $resource = UploadedFileResource::class;

    protected string $view = 'filament.resources.uploaded-files.pages.upload-file';
}
