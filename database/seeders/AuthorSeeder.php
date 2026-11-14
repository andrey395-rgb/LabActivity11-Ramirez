<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

/*
 * AuthorSeeder: fills the authors table.
 * Result: 5 authors (my Kagawad + 4 fake ones).
 */
class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // my Kagawad, made by hand (not by the factory)
        // create() inserts one row; it works because these columns are in $fillable
        Author::create([
            'name'     => 'Kagawad Andrey Ramirez',
            'position' => 'Kagawad',
            'email'    => 'kagawad.ramirez@bulletin.test',
        ]);

        // 4 more random authors from AuthorFactory
        Author::factory()->count(4)->create();
    }
}
