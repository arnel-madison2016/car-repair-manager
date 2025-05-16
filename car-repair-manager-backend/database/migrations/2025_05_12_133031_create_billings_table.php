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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_sheet_id')->constrained()->onDelete('cascade'); // Fiche de réparation
            $table->string('invoice_number')->unique();
            $table->decimal('total_ht', 10, 2);  // Total hors taxe
            $table->decimal('tva_tax', 5, 2);        // Pourcentage TVA
            $table->decimal('total_ttc', 10, 2); // Total TTC
            $table->date('billing_date');
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
