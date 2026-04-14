<?php

namespace Database\Seeders;

use App\Models\Host;
use App\Models\UploadedFile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => env('TEST_USER_EMAIL'),
            'password' => Hash::make(env('TEST_USER_PASSWORD'))
        ]);

        Host::factory()->create([
            'name' => 'Laravel Storage',
            'url' => 'http://localhost',
            'type' => 'local',
            'status' => 'active',
            'auth_type' => null,
            'auth_credentials' => '{}',
            'ip_address' => '127.0.0.1',
            'timeout' => fake()->numberBetween(-10000, 10000),
            'last_success_at' => fake()->dateTime(),
            'last_error_at' => fake()->dateTime(),
            'last_error_message' => fake()->text(),
            'settings' => '{}',
        ]);

        Host::factory(5)->create();
        UploadedFile::factory(25)->create();
    }
}
