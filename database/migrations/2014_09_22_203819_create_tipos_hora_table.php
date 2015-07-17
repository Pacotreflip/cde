<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTiposHoraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('Maquinaria.tipos_hora', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('descripcion', 50);
//            $table->timestamps();
//        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Maquinaria.tipos_hora');
    }
}
