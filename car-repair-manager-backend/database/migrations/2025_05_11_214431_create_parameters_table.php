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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('bank_account', 255)->nullable();
            $table->string('phone', 30)->nullable();            
            $table->string('email', 100)->nullable();
            $table->string('url_logo', 255)->nullable();
            $table->string('url_site', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
