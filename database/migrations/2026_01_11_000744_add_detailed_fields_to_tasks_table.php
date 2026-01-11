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
            // Irrigation details
            $table->decimal('water_amount', 10, 2)->nullable(); // Liters
            $table->integer('duration_minutes')->nullable();
            
            // Treatment details (Fertilizer/Pesticide)
            $table->string('material_name')->nullable();
            $table->decimal('dosage', 10, 2)->nullable();
            $table->string('dosage_unit')->nullable(); // e.g., kg/acre, ml/liter
            
            // Harvest details
            $table->decimal('harvest_quantity', 12, 2)->nullable();
            $table->string('harvest_unit')->nullable(); // kg, ton, box
            
            // Shared metadata
            $table->text('system_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
