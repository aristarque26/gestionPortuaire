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
        Schema::create('appartenir', function (Blueprint $table) {
            $table->foreignId('idbateau')->constrained('bateaux');
            $table->foreignId('idquai')->constrained('quais');
            $table->timestamps();
            
            // Clé primaire composée pour éviter les doublons
            $table->primary(['idbateau', 'idquai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartenir');
    }
};
