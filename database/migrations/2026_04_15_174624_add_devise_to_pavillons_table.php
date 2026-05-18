<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pavillons', function (Blueprint $table) {
        $table->enum('devise', ['FC', 'USD'])->default('FC')->after('prix_unitaire');
    });
}

public function down()
{
    Schema::table('pavillons', function (Blueprint $table) {
        $table->dropColumn('devise');
    });
}
};
