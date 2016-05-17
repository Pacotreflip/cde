<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EquipamientoReporteMaterialesRequeridosArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_materiales_requeridos_area', function (Blueprint $table) {
            $table->integer('idmateriales_requeridos_area')->unsigned();
            $table->integer('id_material')->unsigned();
            $table->integer('id_area')->unsigned();
            $table->text('ruta_area')->nullable();
            $table->string('area',100);
            $table->integer('id_tipo_area')->unsigned()->nullable();
            $table->text('ruta_tipo_area')->nullable();
            $table->string('tipo_area',100);
            $table->string('material',255);
            $table->string('unidad',16);
            $table->float('cantidad_requerida')->nullable();
            $table->float('precio_estimado')->nullable();
            $table->integer('id_moneda_requerida')->unsigned()->nullable();
            $table->string('moneda_requerida',50)->nullable();
            $table->float('cantidad_comparativa')->nullable();
            $table->float('precio_proyecto_comparativo')->nullable();
            $table->integer('id_moneda_comparativa')->unsigned()->nullable();
            $table->string('moneda_comparativa',50)->nullable();
            $table->integer('id_clasificador')->unsigned()->nullable();
            $table->string('clasificador',255)->nullable();
            $table->integer('id_familia')->unsigned()->nullable();
            $table->string('familia',255)->nullable();
            $table->float('precio_requerido_moneda_comparativa')->nullable();
            $table->float('precio_comparativa_moneda_comparativa')->nullable();
            $table->float('importe_requerido_moneda_comparativa')->nullable();
            $table->float('importe_comparativa_moneda_comparativa')->nullable();
            $table->integer('id_caso')->unsigned()->nullable();
            $table->string('caso',100)->nullable();
            $table->float('diferencia')->nullable();
            $table->float('sobrecosto')->nullable();
            $table->float('ahorro')->nullable();
            $table->float('indice_variacion')->nullable();
            $table->integer('id_grado_variacion')->unsigned()->nullable();
            $table->string('grado_variacion',100)->nullable();
            $table->string('estilo_grado_variacion',50)->nullable();
            $table->integer('id_error')->unsigned()->nullable();
            $table->text('error')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_materiales_requeridos_area');
    }
}
