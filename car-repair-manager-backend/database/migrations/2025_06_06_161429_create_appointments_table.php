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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('vehicule_id')->constrained('vehicules')->cascadeOnDelete();

            $table->date('selected_date'); // date du rendez-vous
            $table->time('selected_time'); // heure du rendez-vous
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');// pending, confirmed, cancelled, completed
            $table->text('notes')->nullable(); // notes du client ou de l’admin

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
