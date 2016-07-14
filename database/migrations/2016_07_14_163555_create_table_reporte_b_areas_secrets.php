<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporteBAreasSecrets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_areas_secrets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area_reporte')->unsigned();
            $table->string('area_secrets',255);
            $table->foreign('id_area_reporte')
                ->references('id')
                ->on('Equipamiento.reporte_b_areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_areas_secrets');
    }
}
