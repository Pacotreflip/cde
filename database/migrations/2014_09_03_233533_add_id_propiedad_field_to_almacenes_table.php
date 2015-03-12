<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIdPropiedadFieldToAlmacenesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('almacenes', function(Blueprint $table)
		{
            $table->unsignedInteger('id_propiedad')->nullable();

            $table->foreign('id_propiedad', 'FK_almacenes_propiedades')
                ->references('id')
                ->on('maquinaria.propiedades');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('almacenes', function(Blueprint $table)
		{
            $table->dropForeign('FK_almacenes_propiedades');

			$table->dropColumn('id_propiedad');
		});
	}

}
