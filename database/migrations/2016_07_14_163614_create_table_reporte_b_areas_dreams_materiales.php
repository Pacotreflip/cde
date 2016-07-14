<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporteBAreasDreamsMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_areas_dreams_materiales', function (Blueprint $table) {
            $table->integer('id_material')->unsigned();
            $table->integer('id_area_dreams')->unsigned();
            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales')->delete("cascade");
            $table->foreign('id_area_dreams')
                ->references('id')
                ->on('Equipamiento.reporte_b_areas_dreams')->delete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_areas_dreams_materiales');
    }
}
