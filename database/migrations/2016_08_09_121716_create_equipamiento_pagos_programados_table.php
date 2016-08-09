<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipamientoPagosProgramadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.pagos_programados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_transaccion')->unsigned();
            $table->date('fecha');
            $table->float('monto');
            $table->integer('id_usuario');
            $table->text('observaciones');
            $table->timestamps();
            
            $table->foreign('id_transaccion')->references('id_transaccion')->on('dbo.transacciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.pagos_programados', function (Blueprint $table) {
            $table->dropForeign('equipamiento_pagos_programados_id_transaccion_foreign');
        });
        Schema::drop('Equipamiento.pagos_programados');
    }
}
