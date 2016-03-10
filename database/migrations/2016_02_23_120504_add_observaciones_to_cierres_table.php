<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObservacionesToCierresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.cierres', function (Blueprint $table) {
            $table->text('observaciones')->nullable();
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
            $table->dropColumn("observaciones");
        });
    }
}
