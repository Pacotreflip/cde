<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIdCategoriaFieldToAlmacenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->unsignedInteger('id_categoria')->nullable();

            $table->foreign('id_categoria', 'FK_almacenes_categorias')
                ->references('id')
                ->on('maquinaria.categorias');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropForeign('FK_almacenes_categorias');

            $table->dropColumn('id_categoria');
        });
    }
}
