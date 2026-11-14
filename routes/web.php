<?php

/*
 * Web routes: maps each URL to a controller method.
 * ->name() gives the route a name so views can use route('posts.index')
 * instead of hard-coding the URL.
 */

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/posts');  // the home page just sends you to the post list

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');     // list page
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');  // single post, {id} goes to show($id)
