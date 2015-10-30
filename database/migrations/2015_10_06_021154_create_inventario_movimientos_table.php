<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventarioMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.inventario_movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_inventario')->unsigned()->index();
            $table->decimal('cantidad_anterior', 8, 2)->default(0);
            $table->decimal('cantidad_actual', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_inventario')
                ->references('id')
                ->on('Equipamiento.inventarios')
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
        Schema::drop('Equipamiento.inventario_movimientos');
    }
}
