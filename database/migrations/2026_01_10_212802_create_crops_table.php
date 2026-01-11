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
            $table->date('planting_date');
            $table->date('expected_harvest_date')->nullable();
            $table->enum('status', ['growing', 'harvested', 'damaged'])->default('growing');
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
