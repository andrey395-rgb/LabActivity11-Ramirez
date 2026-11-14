<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

/*
 * TagSeeder: my 5 tags, then puts 1-3 tags on every post.
 * Uses attach()/sync() on the relationship, never inserting into post_tag by hand.
 */
class TagSeeder extends Seeder
{
    public function run(): void
    {
        // Bayanihan is my own tag: for community work like clean-ups and tree planting
        $names = ['Purok Ramirez', 'Urgent', 'Libre', 'Schedule', 'Bayanihan'];

        foreach ($names as $name) {
            Tag::create(['name' => $name]);
        }

        $tags = Tag::all();

        // 1 to 3 random tags per post
        foreach (Post::all() as $post) {
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }

        // pinned post always has exactly these two.
        // sync() removes the random ones attached above and keeps only these
        $pinned = Post::where('is_pinned', true)->first();
        $mustHave = Tag::whereIn('name', ['Purok Ramirez', 'Urgent'])->pluck('id');
        $pinned->tags()->sync($mustHave);
    }
}
