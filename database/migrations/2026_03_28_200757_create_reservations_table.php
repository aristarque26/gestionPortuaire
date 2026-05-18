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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_reservation');
            $table->enum('type_reservation', ['passage', 'mixte', 'cargaison'])->default('passage');
            $table->integer('nombre_cargaison')->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('poids_cargaison')->nullable();
            $table->dateTime('date_embarquement');
            $table->dateTime('date_arrivee')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'annule', 'arrive'])->default('en_attente');
            $table->foreignId('idvoyage')->constrained('voyages');
            $table->foreignId('idclient')->constrained('client');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
