<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pavillons', function (Blueprint $table) {
            $table->decimal('prix_unitaire', 15, 2)->default(0)->after('unite');
        });
    }

    public function down(): void
    {
        Schema::table('pavillons', function (Blueprint $table) {
            $table->dropColumn('prix_unitaire');
        });
    }
};