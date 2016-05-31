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
            $table->float('cantidad');
            $table->float('cantidad_comparativa');
            $table->float('importe_presupuesto_manual');
            $table->float('importe_presupuesto_comparativa_manual');
            $table->float('importe_presupuesto_calculado');
            $table->float('importe_presupuesto_comparativa_calculado');
            $table->float('importe_compras_emitidas');
            $table->float('pax');
            $table->float('pax_comparativa');
            $table->integer('id_moneda')->unsigned()->index()->default(2);
            $table->float('metros_cuadrados');
            $table->timestamps();
            
            $table->foreign('id_moneda')->references('id')->on('monedas');

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
            $table->dropForeign('id_moneda');
        });
        Schema::drop('Equipamiento.reporte_tipo');
    }
}
