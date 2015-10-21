<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned()->index();
            $table->integer('id_area')->unsigned()->index();
            $table->integer('id_material')->unsigned()->index();
            $table->decimal('cantidad_existencia', 12, 2);
            $table->timestamps();

            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales')
                ->onUpdate('cascade')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.inventarios');
    }
}
