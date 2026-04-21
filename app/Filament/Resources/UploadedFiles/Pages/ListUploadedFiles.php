<?php

namespace App\Filament\Resources\UploadedFiles\Pages;

use App\Filament\Resources\UploadedFiles\UploadedFileResource;
use App\Mail\TestEmail;
use App\Services\Reports\ReportExportService;
use App\Services\Reports\UploadedFilesReport;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ListUploadedFiles extends ListRecords
{
    protected static string $resource = UploadedFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload_file')
                ->label('Upload File')
                ->button()
                ->url(fn() => UploadFile::getUrl()),
            Action::make('send_test_email')
                ->label('Send Test Email')
                ->button()
                ->action(fn() =>
                Mail::to('example@example.com')->send(new TestEmail())),
            Action::make('csv export')
                ->label('CSV')
                ->button()
                ->url(route('report.uploaded-files'))
                ->postToUrl(true)
                ->icon(Heroicon::DocumentArrowDown)
        ];
    }
}
