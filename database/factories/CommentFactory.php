<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
 * CommentFactory: fake comment with a one-sentence body.
 * CommentSeeder overrides post_id and user_id with existing rows.
 */

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'body'    => fake()->sentence(),
        ];
    }
}
