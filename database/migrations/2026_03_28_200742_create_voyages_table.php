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
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->string('code_voyage', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->enum('statut', ['prevu', 'en_cours', 'termine', 'annule'])->default('prevu');
            $table->dateTime('date_depart');
            $table->foreignId('idbateau')->constrained('bateaux');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyages');
    }
};
