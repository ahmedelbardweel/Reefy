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
            $table->string('soil_type')->nullable()->after('area');
            $table->string('irrigation_method')->nullable()->after('soil_type');
            $table->string('seed_source')->nullable()->after('irrigation_method');
            $table->float('yield_estimate')->nullable()->after('seed_source'); // Estimated yield in Tons
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crops', function (Blueprint $table) {
            //
        });
    }
};
