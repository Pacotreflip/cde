<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReporteTipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.reporte_tipo', function (Blueprint $table) {
            $table->increments('id', 10);
            $table->string('tipo', 100);
            $table->float('cantidad')->default(0);
            $table->float('cantidad_comparativa')->default(0);
            $table->float('importe_presupuesto_manual')->default(0);
            $table->float('importe_presupuesto_comparativa_manual')->default(0);
            $table->float('importe_presupuesto_calculado')->default(0);
            $table->float('importe_presupuesto_comparativa_calculado')->default(0);
            $table->float('importe_compras_emitidas')->default(0);
            $table->float('pax')->default(0);
            $table->float('pax_comparativa')->default(0);
            $table->float('numero_modulos')->default(0);
            $table->float('numero_modulos_comparativa')->default(0);
            $table->integer('id_moneda')->unsigned()->default(2);
            $table->float('metros_cuadrados')->default(0);
            $table->timestamps();
            
            $table->foreign('id_moneda')->references('id_moneda')->on('monedas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.reporte_tipo', function (Blueprint $table) {
            $table->dropForeign('equipamiento_reporte_tipo_id_moneda_foreign');
        });
        Schema::drop('Equipamiento.reporte_tipo');
    }
}
