<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'url' => fake()->url(),
            'type' => fake()->randomElement(["webdav","rest_api"]),
            'status' => fake()->randomElement(["active","inactive"]),
            'auth_type' => fake()->randomElement(["basic","bearer","hmac"]),
            'auth_credentials' => '{}',
            'ip_address' => fake()->word(),
            'timeout' => fake()->numberBetween(-10000, 10000),
            'last_success_at' => fake()->dateTime(),
            'last_error_at' => fake()->dateTime(),
            'last_error_message' => fake()->text(),
            'settings' => fake()->word(),
        ];
    }
}
