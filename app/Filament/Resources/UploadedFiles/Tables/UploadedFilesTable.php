<?php

namespace App\Filament\Resources\UploadedFiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UploadedFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('host.name')
                    ->searchable(),
                TextColumn::make('source_path')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('zip_size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('number_of_file')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dataset_type')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
