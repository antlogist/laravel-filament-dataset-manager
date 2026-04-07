<?php

namespace App\Filament\Resources\UploadedFiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UploadedFileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('host.name')
                    ->label('Host'),
                TextEntry::make('source_path'),
                TextEntry::make('name'),
                TextEntry::make('size_bytes')
                    ->numeric(),
                TextEntry::make('zip_size_bytes')
                    ->numeric(),
                TextEntry::make('number_of_file')
                    ->numeric(),
                TextEntry::make('dataset_type')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
