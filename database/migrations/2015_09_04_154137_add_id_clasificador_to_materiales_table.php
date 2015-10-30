<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdClasificadorToMaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->integer('id_clasificador')->nullable();

            $table->foreign('id_clasificador')
                ->references('id')
                ->on('Equipamiento.material_clasificadores')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->dropForeign('materiales_id_clasificador_foreign');
            $table->dropColumn('id_clasificador');
        });
    }
}
