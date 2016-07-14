<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntregasProgramadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.entregas_programadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->float('cantidad_programada');
            $table->float('cantidad_recibida')->nullable();
            $table->date('fecha_entrega');
            $table->text('observaciones');
            $table->integer('id_usuario')->nullable();
            $table->timestamps();
            
            $table->foreign('id_item')
                ->references('id_item')
                ->on('items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.entregas_programadas');
    }
}
