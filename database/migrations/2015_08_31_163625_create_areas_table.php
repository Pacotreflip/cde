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
            $table->unsignedInteger('tipo_id')->nullable();
            $table->string('nombre', 100);
            $table->string('clave', 50)->nullable();
            $table->text('descripcion')->default('');
            NestedSet::columns($table);
            $table->timestamps();
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
