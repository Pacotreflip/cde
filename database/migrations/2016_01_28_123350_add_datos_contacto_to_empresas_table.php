<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatosContactoToEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('nombre_contacto',60)->nullable()->after('nombre_corto');
            $table->string('correo',50)->nullable()->after('nombre_contacto');
            $table->string('telefono',30)->nullable()->after('correo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('nombre_contacto');
            $table->dropColumn('correo');
            $table->dropColumn('telefono');
        });
    }
}
