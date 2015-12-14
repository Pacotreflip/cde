<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialesRequeridosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.materiales_requeridos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tipo_area')->unsigned()->index();
            $table->integer('id_material')->unsigned()->index();
            $table->integer('cantidad_requerida')->default(1);
            $table->integer('id_moneda')->unsigned()->nullable()->index();
            $table->decimal('precio_estimado', 9, 2)->default(0)->nullable();
            $table->boolean('se_evalua')->default(false);
            $table->decimal('cantidad_comparativa', 9, 2)->nullable();
            $table->integer('id_moneda_comparativa')->unsigned()->nullable()->index();
            $table->decimal('precio_comparativa', 9, 2)->nullable();
            $table->boolean('existe_para_comparativa')->default(true);
            $table->timestamps();

            $table->unique(['id_tipo_area', 'id_material']);

            $table->foreign('id_tipo_area')
                ->references('id')
                ->on('Equipamiento.areas_tipo');

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales');

            $table->foreign('id_moneda')
                ->references('id_moneda')
                ->on('monedas');

            $table->foreign('id_moneda_comparativa')
                ->references('id_moneda')
                ->on('monedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.materiales_requeridos');
    }
}
