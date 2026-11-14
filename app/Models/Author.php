<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $fillable = ['name', 'position', 'email'];
}
