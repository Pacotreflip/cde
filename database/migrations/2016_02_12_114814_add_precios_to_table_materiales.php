<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreciosToTableMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materiales', function (Blueprint $table) {
            $table->decimal('precio_estimado', 9, 2)->default(0)->nullable()->after('color');
            $table->decimal('precio_proyecto_comparativo', 9, 2)->default(0)->nullable()->after('precio_estimado');
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
            $table->dropColumn('precio_estimado');
            $table->dropColumn('precio_comparativa');
        });
    }
}
