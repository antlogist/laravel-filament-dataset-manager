<?php

namespace App\Filament\Resources\Hosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                Select::make('type')
                    ->options(['webdav' => 'Webdav', 'local' => 'Local', 's3' => 'S3'])
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->required(),
                Select::make('auth_type')
                    ->options(['basic' => 'Basic', 'bearer' => 'Bearer', 'hmac' => 'Hmac']),
                TextInput::make('auth_credentials'),
                TextInput::make('ip_address')
                    ->required(),
                TextInput::make('timeout')
                    ->required()
                    ->numeric()
                    ->default(30),
                DateTimePicker::make('last_success_at'),
                DateTimePicker::make('last_error_at'),
                Textarea::make('last_error_message')
                    ->columnSpanFull(),
                TextInput::make('settings'),
            ]);
    }
}
