<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.transferencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned()->index();
            $table->integer('id_area_origen')->unsigned()->index();
            $table->integer('numero_folio')->unsigned();
            $table->datetime('fecha');
            $table->text('observaciones')->nullable();
            $table->string('creado_por', 16);
            $table->timestamps();

            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras')
                ->onDelete('cascade');

            $table->foreign('id_area_origen')
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
        Schema::drop('Equipamiento.transferencias');
    }
}
