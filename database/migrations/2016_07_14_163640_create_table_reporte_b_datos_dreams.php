<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporteBDatosDreams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_datos_dreams', function (Blueprint $table) {
            $table->integer('id_cotizacion')->unsigned();
            $table->integer('id_oc')->unsigned()->nullable();
            $table->integer('id_material')->unsigned();
            $table->string('material',255);
            $table->text('material_largo');
            $table->integer('id_familia')->unsigned();
            $table->string('familia',255);
            $table->integer('id_area_reporte')->unsigned();
            $table->string('area_reporte',255);
            $table->integer('id_area_dreams')->unsigned();
            $table->string('area_dreams',255);
            $table->string('unidad',30);
            $table->integer('id_moneda_original')->unsigned();
            $table->string('moneda_original',50);
            $table->float('cantidad');
            $table->float('precio_original')->nullable();
            $table->float('importe_original')->nullable();
            $table->float('importe_dolares')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_datos_dreams');
    }
}
