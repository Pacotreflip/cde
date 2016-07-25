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
        Schema::create('Equipamiento.reporte_b_datos_secrets', function(Blueprint $table)
        {
            $table->integer('no')->nullable();
            $table->string('proveedor')->nullable();
            $table->string('no_oc', 50)->nullable();
            $table->string('descripcion_producto_oc')->nullable();
            $table->integer('id_familia')->nullable();
            $table->string('familia')->nullable();
            $table->integer('id_area_secrets')->nullable();
            $table->string('area_secrets')->nullable();
            $table->integer('id_area_reporte')->nullable();
            $table->string('area_reporte')->nullable();
            $table->integer('id_tipo')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('id_moneda_original')->nullable();
            $table->string('moneda_original', 50)->nullable();
            $table->float('cantidad_comprada', 53, 0)->nullable();
            $table->float('recibidos_por_factura', 53, 0)->nullable();
            $table->string('unidad', 50)->nullable();
            $table->float('precio', 53, 0)->nullable();
            $table->string('moneda', 50)->nullable();
            $table->float('importe_sin_iva', 53, 0)->nullable();
            $table->string('fecha_factura')->nullable();
            $table->string('factura')->nullable();
            $table->string('fecha_pago')->nullable();
            $table->string('area_amr')->nullable();
            $table->string('fecha_entrega')->nullable();
            $table->float('pesos', 53, 0)->nullable();
            $table->float('dolares', 53, 0)->nullable();
            $table->float('euros', 53, 0)->nullable();
            $table->float('consolidado_dolares', 53, 0)->nullable();
            $table->integer('id_material_secrets')->nullable();
            $table->string('proveedor_dreams')->nullable();
            $table->string('no_oc_dreams', 50)->nullable();
            $table->string('descripcion_producto_oc_dreams')->nullable();
            $table->integer('id_familia_dreams')->nullable();
            $table->string('familia_dreams')->nullable();
            $table->integer('id_area_dreams')->nullable();
            $table->string('area_dreams')->nullable();
            $table->integer('id_area_reporte_p_dreams')->nullable();
            $table->string('area_reporte_p_dreams')->nullable();
            $table->integer('id_tipo_dreams')->nullable();
            $table->string('tipo_dreams')->nullable();
            $table->float('cantidad_comprada_dreams', 53, 0)->nullable();
            $table->float('cantidad_recibida_dreams', 53, 0)->nullable();
            $table->string('unidad_dreams', 50)->nullable();
            $table->float('precio_unitario_antes_descuento_dreams', 53, 0)->nullable();
            $table->float('descuento_dreams', 53, 0)->nullable();
            $table->float('precio_unitario_dreams', 53, 0)->nullable();
            $table->integer('id_moneda_original_dreams')->nullable();
            $table->string('moneda_original_dreams', 50)->nullable();
            $table->float('importe_sin_iva_dreams', 53, 0)->nullable();
            $table->string('fecha_factura_dreams')->nullable();
            $table->string('factura_dreams')->nullable();
            $table->float('pagado_dreams', 53, 0)->nullable();
            $table->string('area_amr_dreams')->nullable();
            $table->string('fecha_entrega_dreams')->nullable();
            $table->float('presupuesto', 53, 0)->nullable();
            $table->float('pesos_dreams', 53, 0)->nullable();
            $table->float('dolares_dreams', 53, 0)->nullable();
            $table->float('euros_dreams', 53, 0)->nullable();
            $table->float('consolidacion_dolares_dreams', 53, 0)->nullable();
            $table->float('costo_x_habitacion_dreams', 53, 0)->nullable();
            $table->float('consolidado_banco_dreams', 53, 0)->nullable();
            $table->float('id_clasificacion', 53, 0)->nullable();
            $table->string('clasificacion')->nullable();
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
