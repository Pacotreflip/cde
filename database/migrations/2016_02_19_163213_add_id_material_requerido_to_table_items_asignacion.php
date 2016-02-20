<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdMaterialRequeridoToTableItemsAsignacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.asignacion_items', function (Blueprint $table) {
            $table->integer('id_material_requerido')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.asignacion_items', function (Blueprint $table) {
            $table->dropColumn("id_material_requerido");
        });
    }
}
