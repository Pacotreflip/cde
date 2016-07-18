<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMsMd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_ms_md', function (Blueprint $table) {
            $table->integer('id_material_dreams')->unsigned();
            $table->integer('id_material_secrets')->unsigned();
            
            $table->foreign('id_material_dreams')
                ->references('id_material')
                ->on('materiales')
                ;
            $table->foreign('id_material_secrets')
                ->references('id')
                ->on('Equipamiento.reporte_b_materiales_secrets')
                ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_ms_md');
    }
}
