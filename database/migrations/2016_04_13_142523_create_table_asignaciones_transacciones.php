<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAsignacionesTransacciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.asignaciones_transacciones', function (Blueprint $table) {
            $table->integer('id_asignacion')->unsigned();
            $table->integer('id_transaccion')->unsigned();
            
            $table->foreign('id_asignacion')
                ->references('id')
                ->on('Equipamiento.asignaciones');
            
            $table->foreign('id_transaccion')
                ->references('id_transaccion')
                ->on('transacciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.asignaciones_transacciones', function (Blueprint $table) {
            $table->dropForeign('equipamiento_asignaciones_transacciones_id_asignacion_foreign');
            $table->dropForeign('equipamiento_asignaciones_transacciones_id_transaccion_foreign');
        });
        Schema::drop('Equipamiento.asignaciones_transacciones');
    }
}
