<?php

/*
 * Migration: adds a "views" counter column to the existing posts table.
 * Kapitan asked for this after the posts table was already migrated, so
 * instead of editing the old migration we add a new one that ALTERS the table.
 * Schema::table (not Schema::create) means "change an existing table".
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * up(): add the column.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // unsignedInteger = whole number that can't be negative
            // default(0) = every existing and new post starts at 0 views
            // after('body') = place the column after body (ignored by SQLite, used by MySQL)
            $table->unsignedInteger('views')->default(0)->after('body');
        });
    }

    /**
     * down(): remove the column again, so rollback puts the table back how it was.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
