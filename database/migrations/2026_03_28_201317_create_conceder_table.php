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
        Schema::create('conceder', function (Blueprint $table) {
            $table->foreignId('idport')->constrained('ports');
            $table->foreignId('idtrajet')->constrained('trajets');
            $table->integer('ordre_etape');
            $table->string('role_port', 50);
            $table->timestamps();
            
            // Clé primaire composée pour éviter les doublons
            $table->primary(['idport', 'idtrajet']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceder');
    }
};
