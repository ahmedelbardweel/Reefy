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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Farmer
            $table->foreignId('expert_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('crop_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('question');
            $table->text('response')->nullable();
            $table->string('status')->default('pending'); // pending, answered
            $table->string('category')->nullable(); // Irrigation, Pests, Fertilizer, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
