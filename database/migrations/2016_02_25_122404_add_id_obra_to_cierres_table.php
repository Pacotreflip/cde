<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdObraToCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.cierres', function (Blueprint $table) {
            $table->integer('id_obra')->unsigned()->index();
            $table->foreign('id_obra')
                ->references('id_obra')
                ->on('obras');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.cierres', function (Blueprint $table) {
            $table->dropForeign('equipamiento_cierres_id_obra_foreign');
            $table->dropIndex('equipamiento_cierres_id_obra_index');
            $table->dropColumn("id_obra");
        });
    }
}
