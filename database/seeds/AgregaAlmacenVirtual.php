<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\Almacen;
use Ghi\Core\Models\Obra;
class AgregaAlmacenVirtual extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obras = Obra::all();
        foreach($obras as $obra){
            Almacen::create([
                "descripcion"=>"Almacén Virtual",
                "tipo_almacen"=>"0",
                "virtual"=>"1",
                "id_obra"=>$obra->id_obra,
            ]);
        }
    }
}
