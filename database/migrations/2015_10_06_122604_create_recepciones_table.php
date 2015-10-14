<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecepcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.recepciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_obra')->unsigned()->index();
            $table->integer('id_empresa')->unsigned()->index();
            $table->integer('id_orden_compra')->unsigned()->index();
            $table->integer('id_area_almacenamiento')->unsigned()->index();
            $table->integer('numero_folio');
            $table->datetime('fecha_recepcion');
            $table->string('referencia_documento')->nullable();
            $table->string('orden_embarque')->nullable();
            $table->string('numero_pedido')->nullable();
            $table->string('persona_recibe');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->foreign('id_empresa')
                ->references('id_empresa')
                ->on('empresas')
                ->onDelete('cascade');

            $table->foreign('id_orden_compra')
                ->references('id_transaccion')
                ->on('transacciones')
                ->onDelete('cascade');

            $table->foreign('id_area_almacenamiento')
                ->references('id')
                ->on('Equipamiento.areas')
                ->onDelete('cascade');

            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.recepciones');
    }
}
