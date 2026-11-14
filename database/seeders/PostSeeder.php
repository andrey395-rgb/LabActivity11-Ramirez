<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;

/*
 * PostSeeder: fills the posts table.
 * Result: 51 posts = 40 published + 10 drafts + 1 pinned post (mine).
 * Must run after AuthorSeeder because every post needs an existing author.
 */
class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get all 5 authors so posts can be spread among them
        $authors = Author::all();

        // 40 published posts
        // sequence() runs for each post, giving it a random existing author's id
        // (otherwise the factory would create a brand new author for every post)
        Post::factory()->count(40)
            ->sequence(fn ($seq) => ['author_id' => $authors->random()->id])
            ->create();

        // 10 drafts (draft() state = is_published false, published_at null)
        Post::factory()->count(10)->draft()
            ->sequence(fn ($seq) => ['author_id' => $authors->random()->id])
            ->create();

        // pinned post by my Kagawad, published on my birthday
        // find my Kagawad by email so we get the right id
        $kagawad = Author::where('email', 'kagawad.ramirez@bulletin.test')->first();

        Post::create([
            'author_id'    => $kagawad->id,
            'title'        => 'Purok Ramirez Clean-up Drive',
            'category'     => 'Announcement',
            'body'         => "Calling all residents of Purok Ramirez! Join our clean-up drive this Saturday starting 6:00 AM. Meeting point is in front of the Barangay Matina Crossing Hall.\n\nPlease bring your own walis tingting, gloves and sako. Coffee and pandesal will be provided after.\n\nLet's keep our purok clean. Salamat!",
            'is_published' => true,
            'is_pinned'    => true,                    // shows first on the list
            'published_at' => '2026-05-20 08:00:00',   // my birthday in 2026
        ]);
    }
}
