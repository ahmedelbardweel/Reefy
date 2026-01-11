<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Posts table: make user_id nullable
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // Comments table: make user_id nullable
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // Likes table: handle constraints and session_id
        if (!Schema::hasColumn('likes', 'session_id')) {
            Schema::table('likes', function (Blueprint $table) {
                $table->string('session_id')->nullable()->after('user_id');
            });
        }

        // Drop constraints using raw SQL to be more certain
        try {
            DB::statement('ALTER TABLE likes DROP FOREIGN KEY likes_user_id_foreign');
        } catch (\Exception $e) {}
        
        try {
            DB::statement('ALTER TABLE likes DROP INDEX likes_post_id_user_id_unique');
        } catch (\Exception $e) {}

        Schema::table('likes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            
            // Try adding the new unique index
            try {
                $table->unique(['post_id', 'user_id', 'session_id']);
            } catch (\Exception $e) {}

            // Re-add foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            try {
                $table->dropUnique(['post_id', 'user_id', 'session_id']);
            } catch (\Exception $e) {}
            
            if (Schema::hasColumn('likes', 'session_id')) {
                $table->dropColumn('session_id');
            }
            
            $table->foreignId('user_id')->nullable(false)->change();
            $table->unique(['post_id', 'user_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
