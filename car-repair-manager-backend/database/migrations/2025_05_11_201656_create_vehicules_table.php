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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->string('vehicle_type', 50)->nullable();         // Type de vehicule: M1 - N1 - L1e - L3e
            $table->string('licence_plate', 150)->nullable();
            $table->string('chassis_number', 150)->nullable();
            $table->string('odometer_reading', 50)->nullable();     // compteur kilometrique
            $table->string('year_registration', 4)->nullable();
            $table->string('fuel_type', 50)->nullable();            
            $table->string('gear_box', 50)->nullable();             // Transmission: manuelle ou automatique   
            $table->string('engine_size', 50)->nullable();
            $table->string('url_pictures', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
