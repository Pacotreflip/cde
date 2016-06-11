<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMaterialesFechasEntrega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.materiales_fechas_entrega', function (Blueprint $table) {
            $table->integer('id_transaccion_orden_compra')->unsigned();
            $table->integer('id_material')->unsigned();
            $table->date('fecha_entrega');
            
            $table->foreign('id_transaccion_orden_compra')
                ->references('id_transaccion')
                ->on('transacciones')
                ->onDelete("cascade");
            
            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales')
                ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.materiales_fechas_entrega');
    }
}
