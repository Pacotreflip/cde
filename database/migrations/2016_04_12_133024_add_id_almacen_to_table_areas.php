<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdAlmacenToTableAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.areas', function (Blueprint $table) {
            $table->integer('id_almacen')->unsigned()->nullable();
            $table->foreign('id_almacen')
                ->references('id_almacen')
                ->on('almacenes');
            //$table->unique("id_almacen");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.areas', function (Blueprint $table) {
            $table->dropForeign('equipamiento_areas_id_almacen_foreign');
            //$table->dropUnique('equipamiento_areas_id_almacen_unique');
            $table->dropColumn("id_almacen");
        });
    }
}
