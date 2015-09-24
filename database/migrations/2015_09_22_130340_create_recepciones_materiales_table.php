<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecepcionesMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepciones_materiales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_recepcion')->index();
            $table->unsignedInteger('id_material')->index();
            $table->decimal('cantidad', 7, 2);
            $table->decimal('precio', 7, 2);
            $table->timestamps();

            $table->foreign('id_recepcion')
                ->references('id')
                ->on('Equipamiento.recepciones')
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
        Schema::drop('Equipamiento.recepciones_materiales');
    }
}
