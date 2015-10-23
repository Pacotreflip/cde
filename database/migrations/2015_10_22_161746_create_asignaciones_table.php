<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.asignaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned()->index();
            $table->integer('numero_folio')->unsigned();
            $table->datetime('fecha_asignacion');
            $table->text('observaciones')->nullable();
            $table->string('creado_por', 16);
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
        Schema::drop('Equipamiento.asignaciones');
    }
}
