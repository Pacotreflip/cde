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
            $table->unsignedInteger('id_tipo_area')->index();
            $table->unsignedInteger('id_material')->index();
            $table->integer('cantidad_requerida')->default(1);
            $table->decimal('costo_estimado', 12, 2)->default(0)->nullable();
            $table->boolean('se_evalua')->default(false);
            $table->integer('cantidad_comparativa')->nullable();
            $table->decimal('precio_comparativa', 12, 2)->nullable();
            $table->boolean('existe_para_comparativa')->default(true);
            $table->timestamps();

            $table->primary(['id_tipo_area', 'id_material']);

            $table->foreign('id_tipo_area')
                ->references('id')
                ->on('Equipamiento.area_tipos');

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
        Schema::drop('Equipamiento.materiales_requeridos');
    }
}
