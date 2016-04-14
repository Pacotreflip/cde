<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAsignacionesTransaccionesItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.asignaciones_transacciones_items', function (Blueprint $table) {
            $table->integer('id_item_asignacion')->unsigned();
            $table->integer('id_item_transaccion')->unsigned();
            
            $table->foreign('id_item_asignacion')
                ->references('id')
                ->on('Equipamiento.asignacion_items');
            
            $table->foreign('id_item_transaccion')
                ->references('id_item')
                ->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.asignaciones_transacciones_items', function (Blueprint $table) {
            $table->dropForeign('equipamiento_asignaciones_transacciones_items_id_item_asignacion_foreign');
            $table->dropForeign('equipamiento_asignaciones_transacciones_items_id_item_transaccion_foreign');
        });
        Schema::drop('Equipamiento.asignaciones_transacciones_items');
    }
}
