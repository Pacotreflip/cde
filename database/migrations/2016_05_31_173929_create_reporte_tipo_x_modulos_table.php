<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReporteTipoXModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_tipo_x_modulos', function (Blueprint $table) {
            $table->integer('id_reporte_tipo')->unsigned()->index();
            $table->integer('id_reporte_modulo')->unsigned()->index();
            $table->float('cantidad');
            $table->timestamps();
            
            $table->foreign('id_reporte_tipo')->references('id')->on('Equipamiento.reporte_tipo');
            $table->foreign('id_reporte_modulo')->references('id')->on('Equipamiento.reporte_modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.reporte_tipo_x_modulos', function (Blueprint $table) {
            $table->dropForeign('id_reporte_tipo');
            $table->dropForeign('id_reporte_modulo');
        });
        Schema::drop('Equipamiento.reporte_tipo_x_modulos');
    }
}
