<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecepcionFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepcion_fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_recepcion')->unsigned();
            $table->string('nombre');
            $table->string('path');
            $table->string('thumbnail_path');
            $table->timestamps();
            
            $table->foreign('id_recepcion')
                ->references('id')
                ->on('Equipamiento.recepciones')
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
        Schema::drop('Equipamiento.recepcion_fotos');
    }
}
