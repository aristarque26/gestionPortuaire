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
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('email', 50)->unique();
            $table->string('telephone', 20);
            $table->string('adresse', 250);
            $table->string('nationalite', 50);
            $table->enum('genre', ['Homme', 'Femme'])->default('Homme');
            $table->string('photo', 255)->nullable();
            $table->dateTime('date_inscription');
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->foreignId('idutilisateur')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
