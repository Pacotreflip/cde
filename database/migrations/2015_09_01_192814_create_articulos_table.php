<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('clasificador_id')->index();
            $table->string('nombre', 500);
            $table->text('descripcion')->default('');
            $table->string('numero_parte', 50)->default('');
            $table->string('ficha_tecnica')->default();
            $table->timestamps();

            $table->foreign('clasificador_id')
                ->references('id')
                ->on('articulo_clasificadores')
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
        Schema::drop('articulos');
    }
}
