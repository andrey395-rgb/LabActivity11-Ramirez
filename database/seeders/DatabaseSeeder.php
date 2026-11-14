<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/*
 * DatabaseSeeder: the main seeder that "php artisan migrate:fresh --seed" runs.
 * It doesn't insert anything itself, it just calls the other seeders in order.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // authors first so posts have someone to belong to
        // (posts.author_id needs an existing author, or the foreign key fails)
        $this->call([
            AuthorSeeder::class,
            PostSeeder::class,
        ]);
    }
}
