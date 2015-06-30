<?php

use Illuminate\Database\Migrations\Migration;

class CreateMaquinariaSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE SCHEMA Maquinaria AUTHORIZATION dbo;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP SCHEMA Maquinaria;');
    }
}
