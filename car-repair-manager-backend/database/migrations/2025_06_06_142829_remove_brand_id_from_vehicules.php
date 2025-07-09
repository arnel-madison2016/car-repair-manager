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
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropForeign(['brand_id']); // Supprimer la contrainte
            $table->dropColumn('brand_id');    // Supprimer la colonne
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable(); // Recréer la colonne
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade'); // Remettre la contrainte
        });
    }
};
