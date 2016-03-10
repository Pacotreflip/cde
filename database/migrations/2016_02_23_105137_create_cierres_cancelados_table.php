<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierresCanceladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.cierres_cancelados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned()->nullable();
            $table->integer('id_usuario');
            $table->integer('id_usuario_cancelo');
            $table->dateTime('cierre_created_at');
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
        Schema::drop('Equipamiento.cierres_cancelados');
    }
}
