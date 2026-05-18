<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 1. Ajout des colonnes (idpavillon OBLIGATOIRE)
            $table->unsignedBigInteger('idpavillon')->after('idclient');
            $table->decimal('prix_total', 15, 2)->default(0)->after('idpavillon');

            // 2. Clé étrangère avec RESTRICT (empêche suppression d’un pavillon utilisé)
            $table->foreign('idpavillon')
                  ->references('id')
                  ->on('pavillons')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['idpavillon']);
            $table->dropColumn(['idpavillon', 'prix_total']);
        });
    }
};