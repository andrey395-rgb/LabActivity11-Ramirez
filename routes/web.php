<?php

/*
 * Web routes: maps each URL to a controller method.
 * ->name() gives the route a name so views can use route('posts.index')
 * instead of hard-coding the URL.
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/posts');  // the home page just sends you to the post list

// login (from Lab 9)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');     // list page

// /posts/create has to be above /posts/{id}, if not "create" is treated as an id -> 404
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');  // single post, {id} goes to show($id)

// author profile page, {author} is route model binding
Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
