<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReporteBDatosSecrets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_b_datos_secrets', function (Blueprint $table) {
            $table->integer('no')->unsigned();
            $table->string('proveedor',255);
            $table->string('no_oc',50);
            $table->text('descripcion_producto_oc');
            $table->integer('id_familia')->unsigned()->nullable();
            $table->string('familia',255);
            $table->integer('id_area_secrets')->unsigned()->nullable();
            $table->string('area_secrets',255);
            $table->integer('id_area_reporte')->unsigned()->nullable();
            $table->string('area_reporte',255)->nullable();
            $table->integer('id_tipo')->unsigned()->nullable();
            $table->string('tipo',255);
            $table->integer('id_moneda_original')->unsigned()->nullable();
            $table->string('moneda_original',50);
            $table->float('cantidad_comprada');
            $table->float('recibidos por factura')->nullable();
            $table->string('unidad',50);
            $table->float('precio')->nullable();
            $table->string('moneda',50);
            $table->float('importe_sin_iva')->nullable();
            $table->string('fecha_factura',12)->nullable();
            $table->string('factura',255)->nullable();
            $table->string('fecha_pago',255)->nullable();
            $table->string('area_amr',255)->nullable();
            $table->string('fecha_entrega',255)->nullable();
            $table->float('pesos')->nullable();
            $table->float('dolares')->nullable();
            $table->float('euros')->nullable();
            $table->float('consolidado_dolares')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.reporte_b_datos_secrets');
    }
}
