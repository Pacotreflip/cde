<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTransferenciasTransaccionesItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.transferencias_transacciones_items', function (Blueprint $table) {
            $table->integer('id_item_transferencia')->unsigned();
            $table->integer('id_item_transaccion')->unsigned();
            
            $table->foreign('id_item_transferencia', 'eq_traf_tra_it_id_item_transferencia_foreign')
                ->references('id')
                ->on('Equipamiento.transferencia_items');
            
            $table->foreign('id_item_transaccion','eq_traf_tra_it_id_item_transaccion_foreign')
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
        Schema::table('Equipamiento.transferencias_transacciones_items', function (Blueprint $table) {
            $table->dropForeign('eq_traf_tra_it_id_item_transferencia_foreign');
            $table->dropForeign('eq_traf_tra_it_id_item_transaccion_foreign');
        });
        Schema::drop('Equipamiento.transferencias_transacciones_items');
    }
}
