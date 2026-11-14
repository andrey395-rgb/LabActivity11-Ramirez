<?php

/*
 * Migration: comments table.
 * A comment belongs to one post and to the user (resident) who wrote it.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // if the post is deleted, its comments go with it
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            // if the user account is deleted, their comments are deleted too
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
