<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportesActividadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Maquinaria.reportes_actividad', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_almacen');
            $table->date('fecha');
            $table->decimal('horometro_inicial', 6, 1)->default(0);
            $table->decimal('horometro_final', 6, 1)->default(0);
            $table->integer('kilometraje_inicial')->default(0);
            $table->integer('kilometraje_final')->default(0);
            $table->string('operador', 50)->nullable();
            $table->boolean('estado')->default(0);
            $table->text('observaciones')->default('');
            $table->string('creado_por', 16);
            $table->timestamps();

            $table->unique(['id_almacen', 'fecha'], 'UQ_reportes_actividad');

            $table->foreign('id_almacen', 'FK_reportes_actividad_almacenes')
                ->references('id_almacen')
                ->on('almacenes')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maquinaria.reportes_actividad');
    }
}
