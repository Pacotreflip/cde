<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ghi\Equipamiento\Areas\Almacen;
use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
class AddTipoAlmacenToTableAlmacenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almacenes', function (Blueprint $table) {
            $table->boolean("virtual")->default(0);
            
        });
        $obras = Obra::all();
        foreach($obras as $obra){
            Almacen::create([
                "descripcion"=>"ALMACÉN VIRTUAL",
                "tipo_almacen"=>"0",
                "virtual"=>"1",
                "id_obra"=>$obra->id_obra,
            ]);
            Area::create([
                "nombre"=>"ALMACÉN SAO",
                "clave"=>"ALM SAO",
                "descripcion"=>"ALMACÉN SAO",
                "id_obra"=>$obra->id_obra,
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
        $almacenes = Almacen::where("virtual", 1)->get();
        foreach($almacenes as $almacen){
            $almacen->delete();
        }
        Schema::table('almacenes', function (Blueprint $table) {
            $table->dropColumn("virtual");
        });
    }
}
