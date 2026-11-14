<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/*
 * AuthorFactory: a "recipe" for making fake authors with Faker.
 * Used by AuthorSeeder: Author::factory()->count(4)->create();
 */

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Default values for one fake author. fake() is Laravel's Faker helper.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'     => fake()->name(),                    // random person name
            'position' => fake()->randomElement(['Kagawad', 'Barangay Secretary', 'BHW', 'Tanod']), // pick one
            'email'    => fake()->unique()->safeEmail(),     // unique() = no repeats (email column is unique)
        ];
    }
}
