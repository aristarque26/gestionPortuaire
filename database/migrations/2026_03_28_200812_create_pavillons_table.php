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
        Schema::create('pavillons', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->integer('capacite_max');
            $table->string('classe', 50);
            $table->string('unite', 50);
            $table->foreignId('idbateau')->constrained('bateaux');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pavillons');
    }
};
