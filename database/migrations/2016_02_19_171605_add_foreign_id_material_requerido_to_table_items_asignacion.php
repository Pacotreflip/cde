<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignIdMaterialRequeridoToTableItemsAsignacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.asignacion_items', function (Blueprint $table) {
            $table->foreign('id_material_requerido')->references("id")->on("Equipamiento.materiales_requeridos_area");
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
            $table->dropForeing("equipamiento_asignacion_items_id_material_requerido_foreign");
        });
    }
}
