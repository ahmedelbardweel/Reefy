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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., Wheat Plot 1
            $table->string('type'); // e.g., Wheat, Corn, Tomato
            $table->double('area')->default(0); // in acres/feddans
            $table->string('soil_type')->nullable();
            $table->string('irrigation_method')->nullable();
            $table->string('seed_source')->nullable();
            $table->float('yield_estimate')->nullable(); // Estimated yield in Tons
            $table->date('planting_date');
            $table->date('expected_harvest_date')->nullable();
            $table->string('status')->default('growing');
            $table->integer('growth_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->string('image_path')->nullable(); // For crop image
            $table->string('growth_stage')->default('seedling'); // e.g. seedling, vegetative
            $table->string('health_status')->default('good'); // e.g. good, pest, disease
            $table->string('variety')->nullable(); // e.g. Cherry Tomato
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
