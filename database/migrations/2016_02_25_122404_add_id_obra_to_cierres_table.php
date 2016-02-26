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
            $table->dropForeign('cierres_id_obra_foreign');
            $table->dropColumn("id_obra");
        });
    }
}
