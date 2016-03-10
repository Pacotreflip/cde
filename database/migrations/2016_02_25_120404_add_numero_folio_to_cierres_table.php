<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumeroFolioToCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.cierres', function (Blueprint $table) {
            $table->integer('numero_folio')->unsigned();
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
            $table->dropColumn("numero_folio");
        });
    }
}
