<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/*
 * UserSeeder: login accounts.
 * Lola Ramirez = resident (can comment only)
 * Kagawad Andrey Ramirez = linked to his author profile (can comment and write posts)
 * + 8 random residents from the factory
 * Runs after AuthorSeeder so the Kagawad author row already exists.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Lola Ramirez',
            'email'    => 'lola.ramirez@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawadUser = User::create([
            'name'     => 'Kagawad Andrey Ramirez',
            'email'    => 'kagawad.ramirez@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        // link the login account to the author profile from Lab 10
        $kagawadAuthor = Author::where('email', 'kagawad.ramirez@bulletin.test')->first();
        $kagawadAuthor->user_id = $kagawadUser->id;
        $kagawadAuthor->save();

        User::factory()->count(8)->create();
    }
}
