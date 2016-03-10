<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.cierres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned()->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
            
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
        Schema::drop('Equipamiento.cierres');
    }
}
