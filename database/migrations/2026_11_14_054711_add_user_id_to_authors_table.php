<?php

/*
 * Migration: links a login account (users) to an author profile.
 * unique() makes it one-to-one: a user can have at most one author row (User hasOne Author).
 * nullable because the fake authors from the factory don't have an account.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            // if the user is deleted, keep the author and their posts, just unlink it
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            // drop the foreign key and the unique index first, then the column
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
