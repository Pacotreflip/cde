<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionItemsValidacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.asignacion_item_validacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item_asignacion')->unsigned();
            $table->integer('id_usuario');
            $table->timestamps();
            
            $table->unique(['id_item_asignacion']);
            
            $table->foreign('id_item_asignacion')
                ->references('id')
                ->on('Equipamiento.asignacion_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.asignacion_item_validacion');
    }
}
