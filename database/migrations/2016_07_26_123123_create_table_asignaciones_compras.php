<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAsignacionesCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignaciones_compra', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_transaccion')->unsigned();
            $table->float('cantidad_asignada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('asignaciones_compra');
    }
}
