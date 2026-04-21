<?php

use App\Services\Reports\UploadedFilesReport;
use Illuminate\Support\Facades\Route;

Route::post('report/uploaded-files', function () {
    UploadedFilesReport::report();
})->name('report.uploaded-files')->middleware('can:download reports');
