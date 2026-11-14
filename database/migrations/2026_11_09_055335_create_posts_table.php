<?php

/*
 * Migration: creates the "posts" table (the bulletin announcements).
 * Note: there is no "views" column here on purpose. It was added later
 * in its own migration (add_views_to_posts_table) instead of editing this one.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * up() runs on "php artisan migrate" and builds the table.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();  // id: auto-increment primary key

            // author_id must match an id in the authors table (foreign key).
            // cascadeOnDelete: if an author is deleted, their posts are deleted too.
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();

            $table->string('title');                          // post title
            $table->string('category');                       // Announcement, Health, Peace & Order, Ayuda, Livelihood
            $table->text('body');                             // the full text (text = longer than string)
            $table->boolean('is_published')->default(false);  // false = draft, hidden from the site
            $table->boolean('is_pinned')->default(false);     // true = always shown first on the list
            $table->timestamp('published_at')->nullable();    // null for drafts (not published yet)
            $table->timestamps();                             // created_at and updated_at
        });
    }

    /**
     * down() runs on "php artisan migrate:rollback" and undoes up().
     */
    public function down(): void
    {
        Schema::dropIfExists('posts'); // delete the whole table
    }
};
