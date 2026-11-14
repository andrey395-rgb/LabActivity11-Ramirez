<?php

/*
 * Migration: creates the "authors" table.
 * Authors are the barangay officials who write posts (Kagawad, Secretary, BHW, Tanod).
 * This file has an earlier timestamp than create_posts_table, so it runs first.
 * That matters because posts.author_id points to authors.id (foreign key).
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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();                       // id: auto-increment primary key
            $table->string('name');             // full name, e.g. "Kagawad Andrey Ramirez"
            $table->string('position');         // Kagawad, Barangay Secretary, BHW, Tanod
            $table->string('email')->unique();  // no two authors can share an email
            $table->timestamps();               // created_at and updated_at columns
        });
    }

    /**
     * down() runs on "php artisan migrate:rollback" and undoes up().
     */
    public function down(): void
    {
        Schema::dropIfExists('authors'); // delete the whole table
    }
};
