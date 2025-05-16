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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained('billings')->cascadeOnDelete(); // lien avec la facture
            $table->enum('method', ['cash', 'card', 'bank_transfer', 'mobile_payment'])->default('cash');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('transaction_reference')->nullable(); // pour CB ou virement
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
