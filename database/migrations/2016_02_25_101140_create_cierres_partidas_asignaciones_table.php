<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierresPartidasAsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.cierres_partidas_asignaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_cierre_partida')->unsigned();
            $table->integer('id_asignacion_item_validacion')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_cierre_partida')
                ->references('id')
                ->on('Equipamiento.cierres_partidas')
                ->onDelete('cascade');
            
            $table->foreign('id_asginacion_item_validacion')
                ->references('id')
                ->on('Equipamiento.asignacion_item_validacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.cierres_partidas_asignaciones');
    }
}
