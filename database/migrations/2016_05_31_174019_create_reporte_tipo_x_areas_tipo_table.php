<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReporteTipoXAreasTipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_tipo_x_areas_tipo', function (Blueprint $table) {
            $table->integer('id_reporte_tipo')->unsigned()->index();
            $table->integer('id_area_tipo')->unsigned()->index();
            $table->timestamps();
            
            $table->foreign('id_reporte_tipo')->references('id')->on('Equipamiento.reporte_tipo');
            $table->foreign('id_area_tipo')->references('id')->on('Equipamiento.areas_tipo');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.reporte_tipo_x_areas_tipo', function (Blueprint $table) {
            $table->dropForeign('equipamiento_reporte_tipo_x_areas_tipo_id_reporte_tipo_foreign');
            $table->dropForeign('equipamiento_reporte_tipo_x_areas_tipo_id_area_tipo_foreign');
        });
        Schema::drop('Equipamiento.reporte_tipo_x_areas_tipo');
    }
}
