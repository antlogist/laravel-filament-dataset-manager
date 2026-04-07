<?php

namespace App\Filament\Resources\UploadedFiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UploadedFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()->unique(),
                Select::make('dataset_type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'code' => 'Code',
                        'text' => 'Text',
                        'tabular' => 'Tabular',
                    ])
                    ->required(),
            ]);
    }
}
