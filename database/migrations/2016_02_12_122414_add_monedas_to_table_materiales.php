<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonedasToTableMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->integer('id_moneda')->unsigned()->nullable()->index()->after('precio_estimado');
            $table->integer('id_moneda_proyecto_comparativo')->unsigned()->nullable()->index()->after('precio_comparativa');
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
            $table->dropColumn('id_moneda');
            $table->dropColumn('id_moneda_comparativa');
        });
    }
}
