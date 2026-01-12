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
        Schema::table('crops', function (Blueprint $table) {
            // Change status to string to permit any value like 'active'
            $table->string('status')->default('growing')->change();
            
            // Add missing columns
            $table->string('image_path')->nullable(); // For crop image
            $table->string('growth_stage')->default('seedling'); // e.g. seedling, vegetative
            $table->string('health_status')->default('good'); // e.g. good, pest, disease
            $table->string('variety')->nullable(); // e.g. Cherry Tomato
            $table->text('description')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crops', function (Blueprint $table) {
            // We cannot easily revert 'status' to enum without raw SQL, keeping as string is safer
            $table->dropColumn(['image_path', 'growth_stage', 'health_status', 'variety', 'description']);
        });
    }
};
