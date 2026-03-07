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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // water, fertilizer, pest, harvest, other, general...
            $table->date('due_date');
            $table->time('due_time')->nullable();
            $table->time('reminder_time')->default('08:00:00');
            $table->string('priority')->default('medium');
            $table->enum('status', ['pending', 'completed', 'missed'])->default('pending');
            $table->text('notes')->nullable();

            // Irrigation details
            $table->decimal('water_amount', 10, 2)->nullable(); // Liters
            $table->integer('duration_minutes')->nullable();

            // Treatment details (Fertilizer/Pesticide)
            $table->string('material_name')->nullable();
            $table->decimal('dosage', 10, 2)->nullable();
            $table->string('dosage_unit')->nullable(); // kg/acre, ml/liter

            // Harvest details
            $table->decimal('harvest_quantity', 12, 2)->nullable();
            $table->string('harvest_unit')->nullable(); // kg, ton, box

            // Shared metadata
            $table->text('system_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
