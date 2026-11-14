<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/*
 * Tag model: labels like Urgent or Libre.
 * A tag can be on many posts and a post can have many tags -> belongsToMany through post_tag.
 */
class Tag extends Model
{
    protected $fillable = ['name'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class); // uses the post_tag pivot table
    }
}
