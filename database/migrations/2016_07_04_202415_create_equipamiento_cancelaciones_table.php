<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipamientoCancelacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.cancelaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned()->index();
            $table->integer('numero_folio_recepcion')->unsigned()->nullable();
            $table->integer('numero_folio_asignacion')->unsigned()->nullable();
            $table->integer('numero_folio_transferencia')->unsigned()->nullable();
            $table->integer('numero_folio_cierre')->unsigned()->nullable();
            $table->integer('numero_folio_entrega')->unsigned()->nullable();
            $table->text('motivo');
            $table->integer('id_usuario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.cancelaciones');
    }
}
