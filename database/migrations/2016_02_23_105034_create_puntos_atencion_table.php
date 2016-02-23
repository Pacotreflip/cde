<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePuntosAtencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.puntos_atencion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned();
            $table->integer('id_estado')->unsigned();
            $table->text('descripcion');
            $table->integer('id_usuario');
            $table->timestamps();
            
            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas')
                ->onDelete('cascade');
            
            $table->foreign('id_estado')
                ->references('id')
                ->on('Equipamiento.ctg_estados_pa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.puntos_atencion');
    }
}
