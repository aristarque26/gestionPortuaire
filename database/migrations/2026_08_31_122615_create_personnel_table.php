<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            
            // 🔗 Clé étrangère vers users (sans ON DELETE CASCADE)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete(); // ✅ Empêche la suppression si un agent est lié

            // 📌 Informations spécifiques au personnel
            $table->string('matricule', 50)->unique();
            $table->string('poste', 100);
            $table->string('service', 100);
            $table->date('date_embauche');
            $table->enum('personnel_role', [
                'superviseur',
                'comptable',
                'caissier',
                'agent_portuaire',
                'gestionnaire'
            ])->default('agent_portuaire');
            $table->decimal('salaire', 15, 2);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personnel');
    }
};