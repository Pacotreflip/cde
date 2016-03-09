<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToCierresPartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.cierres_partidas', function (Blueprint $table) {
            $table->unique("id_area");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.cierres_partidas', function (Blueprint $table) {
            $table->dropUnique("equipamiento_cierres_partidas_id_area_unique");
        });
    }
}
