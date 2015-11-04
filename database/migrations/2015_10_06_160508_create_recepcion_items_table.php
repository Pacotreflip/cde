<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecepcionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepcion_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_recepcion')->unsigned()->index();
            $table->integer('id_item')->unsigned()->index();
            $table->integer('id_material')->unsigned()->index();
            $table->string('unidad');
            $table->integer('id_area_almacenamiento')->nullable()->unsigned()->index();
            $table->decimal('cantidad_recibida', 8, 2);
            $table->timestamps();

            $table->foreign('id_recepcion')
                ->references('id')
                ->on('Equipamiento.recepciones')
                ->onDelete('cascade');

            $table->foreign('id_item')
                ->references('id_item')
                ->on('items');

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales');

            $table->foreign('id_area_almacenamiento')
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
        Schema::drop('Equipamiento.recepcion_items');
    }
}
