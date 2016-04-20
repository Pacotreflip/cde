<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ghi\Equipamiento\Areas\Concepto;
use Ghi\Core\Models\Obra;
class AddIdConceptoToEquipamientoAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Equipamiento.areas', function (Blueprint $table) {
            $table->integer('id_concepto')->unsigned()->nullable();
            $table->foreign('id_concepto')
                ->references('id_concepto')
                ->on('conceptos');
        });
        Schema::table('conceptos', function (Blueprint $table) {
            $table->boolean("control_equipamiento")->default(0);
        });
        $obras = Obra::all();
        foreach($obras as $obra){
            Concepto::create([
                "descripcion"=>"CONTROL DE EQUIPAMIENTO",
                "id_obra"=>$obra->id_obra,
                "control_equipamiento"=>1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('Equipamiento.areas', function (Blueprint $table) {
            $table->dropForeign('equipamiento_areas_id_concepto_foreign');
            $table->dropColumn("id_concepto");
        });
        
        $conceptos_equipamiento = Concepto::where("control_equipamiento", 1)->get();
        foreach($conceptos_equipamiento as $concepto_equipamiento){
            $concepto_equipamiento->delete();
        }
        Schema::table('conceptos', function (Blueprint $table) {
            $table->dropColumn("control_equipamiento");
        });
        
    }
}
