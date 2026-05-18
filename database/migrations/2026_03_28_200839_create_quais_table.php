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
        Schema::create('quais', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->integer('capacite');
            $table->enum('type_quai', ['passager', 'cargaison', 'mixte'])->default('mixte');
            $table->enum('statut', ['libre', 'occupe', 'maintenance'])->default('libre');
            $table->integer('numero');
            $table->foreignId('idport')->constrained('ports');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quais');
    }
};
