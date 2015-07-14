<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Maquinaria.actividades', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_reporte')->index();
            $table->unsignedInteger('id_tipo_hora')-index();
            $table->unsignedInteger('id_turno')->nullable();
            $table->unsignedInteger('id_concepto')->nullable();
            $table->time('hora_inicial')->nullable();
            $table->time('hora_final')->nullable();
            $table->decimal('cantidad', 5, 2);
            $table->boolean('con_cargo')->default(false);
            $table->text('observaciones')->default('');
            $table->string('creado_por', 16);
            $table->timestamps();

            $table->foreign('id_reporte', 'FK_actividades_reportes_actividad')
                ->references('id')
                ->on('Maquinaria.reportes_actividad')
                ->onDelete('cascade');

            $table->foreign('id_tipo_hora', 'FK_actividades_tipos_hora')
                ->references('id')
                ->on('Maquinaria.tipos_hora');

            $table->foreign('id_turno', 'FK_actividades_turnos')
                ->references('id')
                ->on('Maquinaria.turnos');

            $table->foreign('id_concepto', 'FK_actividades_conceptos')
                ->references('id_concepto')
                ->on('conceptos')
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
        Schema::drop('Maquinaria.actividades');
    }
}
