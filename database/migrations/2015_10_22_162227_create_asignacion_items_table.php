<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.asignacion_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_asignacion')->unsigned()->index();
            $table->integer('id_material')->unsigned()->index();
            $table->string('unidad');
            $table->integer('id_area_origen')->unsigned()->index();
            $table->integer('id_area_destino')->unsigned()->index();
            $table->decimal('cantidad_asignada', 8, 2);
            $table->timestamps();

            $table->foreign('id_asignacion')
                ->references('id')
                ->on('Equipamiento.asignaciones')
                ->onDelete('cascade');
            
            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales');

            $table->foreign('id_area_origen')
                ->references('id')
                ->on('Equipamiento.areas');

            $table->foreign('id_area_destino')
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
        Schema::drop('Equipamiento.asignacion_items');
    }
}
