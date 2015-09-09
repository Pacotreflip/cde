<?php

use Kalnoy\Nestedset\NestedSet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_obra')->index();
            $table->unsignedInteger('tipo_id')->nullable();
            $table->string('nombre', 100);
            $table->string('clave', 50)->nullable();
            $table->text('descripcion')->default('');
            NestedSet::columns($table);
            $table->timestamps();

            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras')
                ->onDelete('cascade');

            $table->foreign('tipo_id')
                ->references('id')
                ->on('Equipamiento.area_tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.areas');
    }
}
