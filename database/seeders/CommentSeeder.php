<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

/*
 * CommentSeeder: 0-5 random comments on each published post,
 * plus my own comments on the pinned clean-up drive post.
 */
class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // drafts don't get comments since nobody can see them yet
        $published = Post::where('is_published', true)->where('is_pinned', false)->get();

        foreach ($published as $post) {
            Comment::factory()->count(rand(0, 5))
                ->sequence(fn ($seq) => ['user_id' => $users->random()->id])
                ->create(['post_id' => $post->id]);
        }

        // --- pinned post comments ---
        $pinned = Post::where('is_pinned', true)->first();
        $lola = User::where('email', 'lola.ramirez@bulletin.test')->first();
        $kagawad = User::where('email', 'kagawad.ramirez@bulletin.test')->first();
        $resident = $users->whereNotIn('id', [$lola->id, $kagawad->id])->random();

        $pinned->comments()->create([
            'user_id' => $lola->id,
            'body'    => 'Salamat Kagawad! Magdala ko ug pandesal para sa mga bata.',
        ]);

        $pinned->comments()->create([
            'user_id' => $resident->id,
            'body'    => 'Asa mi mag-meet if mag-ulan? Sa covered court ba?',
        ]);

        $pinned->comments()->create([
            'user_id' => $kagawad->id,
            'body'    => 'Yes, sa covered court ta if mag-ulan. Kita-kits sa Sabado!',
        ]);
    }
}
