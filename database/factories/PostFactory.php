<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
 * PostFactory: a "recipe" for making fake posts with Faker.
 * Default = a published post. ->draft() = an unpublished post.
 * Used by PostSeeder.
 */

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Default values for one fake post.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // makes a new fake author if none is given
            // (PostSeeder overrides this with an existing author's id)
            'author_id'    => Author::factory(),
            'title'        => fake()->sentence(6),           // random sentence of about 6 words
            'category'     => fake()->randomElement(['Announcement', 'Health',
                                  'Peace & Order', 'Ayuda', 'Livelihood']), // only my 5 categories
            'body'         => fake()->paragraphs(3, true),   // 3 paragraphs joined into one string
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'), // random date in the last 3 months
        ];
    }

    // draft() state: same as definition() but overrides these two fields.
    // Usage: Post::factory()->draft()->create();
    public function draft(): static
    {
        return $this->state(fn () => ['is_published' => false, 'published_at' => null]);
    }
}
