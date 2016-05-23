<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEquipamientoReporteMaterialesOrdenCompra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_materiales_orden_compra', function (Blueprint $table) {
            $table->integer('id_obra')->unsigned();
            $table->integer('id_material')->unsigned();
            $table->integer('id_orden_compra')->unsigned();
            $table->integer('numero_folio_orden_compra');
            $table->string('material',255);
            $table->string('unidad',16);
            $table->float('cantidad_compra')->nullable();
            $table->float('precio_compra')->nullable();
            $table->integer('id_moneda_compra')->unsigned()->nullable();
            $table->string('moneda_compra',50)->nullable();
            $table->float('precio_compra_moneda_comparativa')->nullable();
            $table->float('importe_compra_moneda_comparativa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
