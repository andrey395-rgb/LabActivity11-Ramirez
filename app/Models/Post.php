<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Post model (Eloquent / ActiveRecord).
 * One Post object = one row in the "posts" table.
 * ActiveRecord means the object can save itself: $post->title = '...'; $post->save();
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory; // lets us call Post::factory() to make fake posts (PostFactory)

    // Columns allowed in Post::create([...]) (mass assignment).
    // Any column missing here would throw an error or be saved as NULL.
    protected $fillable = ['author_id', 'title', 'category', 'body',
                           'is_published', 'is_pinned', 'published_at', 'views'];

    // Casts convert database values into proper PHP types when we read them.
    protected $casts = [
        'is_published' => 'boolean',  // SQLite stores 0/1, this gives us true/false
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime', // gives a Carbon date, so ->format('M d, Y') works in the views
    ];
}
