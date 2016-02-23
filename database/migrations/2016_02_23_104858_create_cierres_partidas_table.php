<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierresPartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.cierres_partidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_cierre')->unsigned();
            $table->integer('id_area')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_cierre')
                ->references('id')
                ->on('Equipamiento.cierres')
                ->onDelete('cascade');
            
            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.cierres_partidas');
    }
}
