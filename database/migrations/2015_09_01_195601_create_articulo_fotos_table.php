<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticuloFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_fotos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('articulo_id')->index();
            $table->string('nombre');
            $table->string('path');
            $table->timestamps();

            $table->foreign('articulo_id')
                ->references('id')
                ->on('articulos')
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
        Schema::drop('articulo_fotos');
    }
}
