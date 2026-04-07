<?php

namespace Database\Factories;

use App\Models\Host;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UploadedFileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'host_id' => Host::factory(),
            'source_path' => fake()->word(),
            'name' => fake()->name(),
            'size_bytes' => fake()->numberBetween(-10000, 10000),
            'zip_size_bytes' => fake()->numberBetween(-10000, 10000),
            'number_of_file' => fake()->numberBetween(-10000, 10000),
            'dataset_type' => fake()->randomElement(["image","video","code","text","tabular"]),
            'hash' => fake()->word(),
        ];
    }
}
