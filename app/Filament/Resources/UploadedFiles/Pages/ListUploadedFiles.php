<?php

namespace App\Filament\Resources\UploadedFiles\Pages;

use App\Filament\Resources\UploadedFiles\UploadedFileResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListUploadedFiles extends ListRecords
{
    protected static string $resource = UploadedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload_file')
                ->label('Upload File')
                ->button()
                ->url(fn() => UploadFile::getUrl())
        ];
    }
}
