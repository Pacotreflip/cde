<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporteBAcumuladoAreaFamilia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_acumulado_area_familia', function (Blueprint $table) {
            $table->integer('id_tipo')->unsigned();
            $table->string('tipo',5);
            $table->integer('id_area_reporte')->unsigned();
            $table->string('area_reporte',255);
            $table->integer('id_familia')->unsigned();
            $table->string('familia',255);
            $table->boolean('sin_cotizar')->default(false);
            $table->boolean('cotizado')->default(false);
            $table->boolean('comprado')->default(false);
            $table->float('monto_secrets')->nullable();
            $table->float('monto_dreams')->nullable();
            $table->float('monto_presupuesto')->nullable();
            $table->float('monto_variacion_presupuesto')->nullable();
            $table->float('porcentaje_variacion')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_acumulado_area_familia');
    }
}
