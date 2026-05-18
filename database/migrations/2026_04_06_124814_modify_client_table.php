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
         Schema::table('client', function (Blueprint $table) {
        $table->text('adresse')->nullable()->change();
        $table->string('nationalite', 50)->nullable()->change();
        $table->enum('genre', ['Homme', 'Femme', 'Autre'])->nullable()->change();
        $table->string('photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client', function (Blueprint $table) {
        $table->text('adresse')->nullable(false)->change();
        $table->string('nationalite', 50)->nullable(false)->change();
        $table->enum('genre', ['Homme', 'Femme', 'Autre'])->nullable(false)->change();
        $table->string('photo')->nullable(false)->change();
        });
    }
};
