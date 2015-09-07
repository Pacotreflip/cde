<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.material_fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_material')->index();
            $table->string('nombre');
            $table->string('path');
            $table->string('thumbnail_path');
            $table->timestamps();

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.material_fotos');
    }
}
