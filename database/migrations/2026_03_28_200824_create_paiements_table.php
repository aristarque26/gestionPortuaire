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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 15, 2);
            $table->enum('devise', ['USD', 'CDF'])->default('CDF');
            $table->enum('mode_paiement', ['MOMO', 'CASH', 'VIREMENT'])->default('CASH');
            $table->dateTime('date_paiement');
            $table->enum('statut', ['paye', 'en_attente', 'echoue', 'rembourse'])->default('en_attente');
            $table->foreignId('idreservation')->constrained('reservations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
