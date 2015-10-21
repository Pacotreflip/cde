<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.requerimientos', function (Blueprint $table) {
            $table->unsignedInteger('id_tipo_area')->index();
            $table->unsignedInteger('id_material')->index();
            $table->integer('cantidad_requerida');
            $table->timestamps();

            $table->primary(['id_tipo_area', 'id_material']);

            $table->foreign('id_tipo_area')
                ->references('id')
                ->on('Equipamiento.area_tipos')
                ->onDelete('cascade');

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
        Schema::drop('Equipamiento.requerimientos');
    }
}
