<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\TipoDocumento;
class TiposDocumentoArea extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoDocumento::create(["descripcion"=>"Imagen Principal"]);
        TipoDocumento::create(["descripcion"=>"Ubicación Dentro de Plan Maestro"]);
        TipoDocumento::create(["descripcion"=>"Planta de Mobiliario"]);
    }
}
