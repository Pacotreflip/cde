<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregaPartidaIntegracionCambioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.entrega_partida_integracion_cambio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_entrega')->unsigned();
            $table->char('descripcion',255);
            $table->integer('cantidad');
            $table->char('unidad',10);
            $table->char('ubicacion',255);
            $table->timestamps();
            
            $table->foreign('id_entrega')
                ->references('id')
                ->on('Equipamiento.entregas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.entrega_partida_integracion_cambio');
    }
}
