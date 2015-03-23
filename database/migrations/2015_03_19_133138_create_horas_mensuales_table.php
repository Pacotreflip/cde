<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorasMensualesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Maquinaria.horas_mensuales', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('id_almacen')->unsigned()->index();
            $table->date('inicio_vigencia');
            $table->smallInteger('horas_contrato');
            $table->smallInteger('horas_operacion');
            $table->smallInteger('horas_programa');
            $table->text('observaciones')->nullable();
            $table->string('creado_por', 16);
            $table->string('modificado_por', 16)->nullable();
			$table->timestamps();

            $table->unique('id_almacen', 'inicio_vigencia', 'UQ_horas_mensuales');

            $table->foreign('id_almacen', 'FK_horas_mensuales_almacenes')
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
		Schema::drop('Maquinaria.horas_mensuales');
	}

}
