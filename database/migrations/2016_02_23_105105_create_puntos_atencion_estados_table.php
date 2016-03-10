<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntosAtencionEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.puntos_atencion_estados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_punto_atencion')->unsigned();
            $table->integer('id_usuario');
            $table->timestamps();
            
            $table->foreign('id_punto_atencion')
                ->references('id')
                ->on('Equipamiento.puntos_atencion')
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
        Schema::drop('Equipamiento.puntos_atencion_estados');
    }
}
