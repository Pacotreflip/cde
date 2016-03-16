<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.entregas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned();
            $table->integer('numero_folio')->unsigned();
            $table->datetime('fecha_entrega');
            $table->text('observaciones')->nullable();
            $table->integer('id_usuario')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.entregas');
    }
}
