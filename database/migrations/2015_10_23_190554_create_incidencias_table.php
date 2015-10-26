<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.incidencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned();
            $table->integer('numero_folio')->unsigned();
            $table->datetime('fecha_incidencia');
            $table->string('motivo', 140);
            $table->text('descripcion');
            $table->text('anotaciones')->nullable();
            $table->string('creado_por', 16);
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
        Schema::drop('Equipamiento.incidencias');
    }
}
