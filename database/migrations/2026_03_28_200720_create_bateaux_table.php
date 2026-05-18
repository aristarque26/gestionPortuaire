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
        Schema::create('bateaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->integer('capacite_totale');
            $table->enum('type', ['cargo', 'mixte', 'passager'])->default('mixte');
            $table->string('immatriculation', 50)->unique();
            $table->integer('capacite_passager')->default(0);
            $table->integer('capacite_cargaison')->default(0);
            $table->enum('statut', ['en_service', 'en_maintenance', 'hors_service'])->default('en_service');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bateaux');
    }
};
