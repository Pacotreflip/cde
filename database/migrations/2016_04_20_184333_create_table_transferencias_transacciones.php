<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTransferenciasTransacciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.transferencias_transacciones', function (Blueprint $table) {
            $table->integer('id_transferencia')->unsigned();
            $table->integer('id_transaccion')->unsigned();
            
            $table->foreign('id_transferencia')
                ->references('id')
                ->on('Equipamiento.transferencias');
            
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
        Schema::table('Equipamiento.transferencias_transacciones', function (Blueprint $table) {
            $table->dropForeign('equipamiento_transferencias_transacciones_id_transferencia_foreign');
            $table->dropForeign('equipamiento_transferencias_transacciones_id_transaccion_foreign');
        });
        Schema::drop('Equipamiento.transferencias_transacciones');
    }
}
