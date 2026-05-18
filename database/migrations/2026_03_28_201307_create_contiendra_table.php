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
        Schema::create('contiendra', function (Blueprint $table) {
            $table->foreignId('idpavillon')->constrained('pavillons');
            $table->foreignId('idtrajet')->constrained('trajets');
            $table->decimal('prix', 15, 2);
            $table->timestamps();
            
            // Clé primaire composée pour éviter les doublons
            $table->primary(['idpavillon', 'idtrajet']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contiendra');
    }
};
