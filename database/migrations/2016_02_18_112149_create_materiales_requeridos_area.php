<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialesRequeridosArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.materiales_requeridos_area', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned()->index();
            $table->integer('id_material')->unsigned()->index();
            $table->integer('id_material_requerido')->unsigned()->nullable()->index();
            $table->integer('cantidad_requerida')->default(1);
            $table->boolean('se_evalua')->default(false);
            $table->decimal('cantidad_comparativa', 9, 2)->nullable();
            $table->boolean('existe_para_comparativa')->default(true);
            $table->timestamps();

            $table->unique(['id_area', 'id_material']);

            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas');
            
            $table->foreign('id_material_requerido')
                ->references('id')
                ->on('Equipamiento.materiales_requeridos');

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales');

            
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.materiales_requeridos_area');
    }
}
