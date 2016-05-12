<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EquipamientoReporteVariacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_grado_variacion', function (Blueprint $table) {
            $table->increments('id');
            $table->char('grado_variacion',100);
            $table->double('limite_inferior',15,8);
            $table->double('limite_superior',15,8);
            $table->char('estilo',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_grado_variacion');
    }
}
