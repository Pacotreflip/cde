<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregaPartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.entrega_partidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_entrega')->unsigned();
            $table->integer('id_cierre_partida')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_entrega')
                ->references('id')
                ->on('Equipamiento.entregas');
            
            $table->foreign('id_cierre_partida')
                ->references('id')
                ->on('Equipamiento.cierres_partidas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.entrega_partidas');
    }
}
