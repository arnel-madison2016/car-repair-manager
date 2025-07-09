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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 150);
            $table->string('last_name', 150);
            $table->string('gender', 1);
            $table->string('email', 255);            
            $table->string('phone', 50);
            $table->string('adresse', 255)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('url_photo', 255)->nullable();
            $table->string('profession', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
