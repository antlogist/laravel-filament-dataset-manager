<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Host extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'auth_credentials' => 'array',
            'last_success_at' => 'timestamp',
            'last_error_at' => 'timestamp',
            'settings' => 'array',
        ];
    }

    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(UploadedFile::class);
    }
}
