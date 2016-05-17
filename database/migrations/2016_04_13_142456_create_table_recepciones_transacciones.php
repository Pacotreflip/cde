<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecepcionesTransacciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepciones_transacciones', function (Blueprint $table) {
            $table->integer('id_recepcion')->unsigned();
            $table->integer('id_transaccion')->unsigned();
            
            $table->foreign('id_recepcion')
                ->references('id')
                ->on('Equipamiento.recepciones');
            
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
        Schema::table('Equipamiento.recepciones_transacciones', function (Blueprint $table) {
            $table->dropForeign('equipamiento_recepciones_transacciones_id_recepcion_foreign');
            $table->dropForeign('equipamiento_recepciones_transacciones_id_transaccion_foreign');
        });
        Schema::drop('Equipamiento.recepciones_transacciones');
    }
}
