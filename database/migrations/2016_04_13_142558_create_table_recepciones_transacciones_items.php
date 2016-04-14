<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecepcionesTransaccionesItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepciones_transacciones_items', function (Blueprint $table) {
            $table->integer('id_item_recepcion')->unsigned();
            $table->integer('id_item_transaccion')->unsigned();
            
            $table->foreign('id_item_recepcion', 'eq_rec_tra_it_id_item_recepcion_foreign')
                ->references('id')
                ->on('Equipamiento.recepcion_items');
            
            $table->foreign('id_item_transaccion','eq_rec_tra_it_id_item_transaccion_foreign')
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
        Schema::table('Equipamiento.recepciones_transacciones_items', function (Blueprint $table) {
            $table->dropForeign('eq_rec_tra_it_id_item_recepcion_foreign');
            $table->dropForeign('eq_rec_tra_it_id_item_transaccion_foreign');
        });
        Schema::drop('Equipamiento.recepciones_transacciones_items');
    }
}
