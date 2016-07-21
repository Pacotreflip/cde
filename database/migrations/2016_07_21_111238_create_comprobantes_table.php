<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.comprobantes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_recepcion')->unsigned()->nullable()->default(NULL);
            $table->integer('id_transferencia')->unsigned()->nullable()->default(NULL);
            $table->integer('id_asignacion')->unsigned()->nullable()->default(NULL);
            $table->integer('id_cierre')->unsigned()->nullable()->default(NULL);
            $table->integer('id_entrega')->unsigned()->nullable()->default(NULL);
            $table->string('nombre');
            $table->string('path');
            $table->string('thumbnail_path');
            $table->timestamps();
            $table->foreign('id_recepcion')->references('id')->on('Equipamiento.recepciones')->onDelete('cascade');
            $table->foreign('id_transferencia')->references('id')->on('Equipamiento.transferencias')->onDelete('cascade');
            $table->foreign('id_asignacion')->references('id')->on('Equipamiento.asignaciones')->onDelete('cascade');
            $table->foreign('id_cierre')->references('id')->on('Equipamiento.cierres')->onDelete('cascade');
            $table->foreign('id_entrega')->references('id')->on('Equipamiento.entregas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.comprobantes');
    }
}
