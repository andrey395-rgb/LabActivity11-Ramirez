<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*
 * Comment model: a resident's comment on a post.
 * comments table has post_id and user_id, so it is the "child" side of both links.
 */
class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'body'];

    // the post this comment is under (comments.post_id)
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // the user who wrote the comment (comments.user_id)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
