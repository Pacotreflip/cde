<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EquipamientoAreasAcumuladoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.areas_acumuladores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned();
            $table->float('cantidad_requerida')->nullable();
            $table->float('cantidad_asignada')->nullable();
            $table->float('cantidad_validada')->nullable();
            $table->float('cantidad_areas_cerrables')->nullable();
            $table->float('cantidad_areas_cerradas')->nullable();
            $table->float('cantidad_areas_entregadas')->nullable();
            $table->float('porcentaje_asignacion')->nullable();
            $table->float('porcentaje_validacion')->nullable();
            $table->float('porcentaje_cierre')->nullable();
            $table->float('porcentaje_entrega')->nullable();
            
            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.areas_acumuladores');
    }
}
