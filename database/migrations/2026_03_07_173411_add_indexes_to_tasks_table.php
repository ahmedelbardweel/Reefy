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
        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['type', 'status']); // For dashboard stats
            $table->index('due_date'); // For timeline/calendar
            $table->index('status'); // For pending tasks counts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['type', 'status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['status']);
        });
    }
};
