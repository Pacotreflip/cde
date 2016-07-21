<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposPersonasToTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.transferencias', function (Blueprint $table) {
            $table->string("entrega")->nullable();
            $table->string("recibe")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Equipamiento.transferencias', function (Blueprint $table) {
            $table->dropColumn("entrega");
            $table->dropColumn("recibe");
        });
    }
}
