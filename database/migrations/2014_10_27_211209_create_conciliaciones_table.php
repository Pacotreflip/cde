<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConciliacionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('maquinaria.conciliaciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('id_obra');
			$table->unsignedInteger('id_empresa');
			$table->unsignedInteger('id_almacen');
			$table->date('fecha_inicial');
			$table->date('fecha_final');
			$table->smallInteger('dias_con_operacion');
			$table->smallInteger('horas_contrato');
			$table->float('factor_contrato_periodo');
			$table->smallInteger('horas_a_conciliar');
			$table->smallInteger('horas_efectivas');
			$table->smallInteger('horas_reparacion_mayor');
			$table->smallInteger('horas_reparacion_menor');
			$table->smallInteger('horas_mantenimiento');
			$table->smallInteger('horas_ocio');
			$table->integer('horometro_inicial')->nullable();
			$table->integer('horometro_final')->nullable();
			$table->smallInteger('horas_horometro')->nullable();
			$table->smallInteger('horas_propuesta')->default(0);
			$table->smallInteger('horas_conciliadas')->default(0);
			$table->smallInteger('horas_conciliadas_efectivas')->default(0);
			$table->smallInteger('horas_conciliadas_reparacion_mayor')->default(0);
			$table->smallInteger('horas_conciliadas_ocio')->default(0);
			$table->boolean('cerrado')->default(false);
			$table->string('usuario', 16);
			$table->timestamps();

			$table->unique([
                'id_obra', 'id_empresa', 'id_almacen', 'fecha_inicial', 'fecha_final'
            ], 'UQ_conciliaciones');

            $table->foreign('id_obra', 'FK_conciliaciones_obras')
                ->references('id_obra')
                ->on('obras');

			$table->foreign('id_empresa', 'FK_conciliaciones_empresas')
                ->references('id_empresa')
                ->on('empresas');

			$table->foreign('id_almacen', 'FK_conciliaciones_almacenes')
                ->references('id_almacen')
                ->on('almacenes');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('maquinaria.conciliaciones');
	}

}
