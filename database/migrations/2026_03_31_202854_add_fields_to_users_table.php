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
        Schema::table('users', function (Blueprint $table) {
        $table->string('prenom')->nullable()->after('name');
        $table->string('telephone', 20)->nullable()->after('prenom');
        $table->enum('role', ['admin', 'personnel'])->default('personnel')->after('telephone');
        $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('role');
        $table->string('photo')->nullable()->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
