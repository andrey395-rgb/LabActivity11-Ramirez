<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/*
 * Author model (Eloquent / ActiveRecord).
 * One Author object = one row in the "authors" table.
 * Laravel guesses the table name from the class name: Author -> authors.
 */
class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory; // lets us call Author::factory() to make fake authors (AuthorFactory)

    // Columns that are allowed to be filled with Author::create([...]).
    // Without this list, create() throws a MassAssignmentException.
    // user_id added in Lab 11 (links the author profile to a login account)
    protected $fillable = ['name', 'position', 'email', 'user_id'];

    // the login account of this author (authors.user_id), null for the fake authors
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // all posts written by this author (posts.author_id)
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // comments on this author's posts: Author -> Post -> Comment
    // there is no author_id in comments, so we "hop" through the posts table
    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
}
