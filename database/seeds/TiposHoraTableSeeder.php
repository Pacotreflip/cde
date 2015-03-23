<?php

use Ghi\Operacion\Domain\TipoHora;
use Illuminate\Database\Seeder;

class TiposHoraTableSeeder extends Seeder {

	public function run()
	{
		TipoHora::create([
            'descripcion' => 'Efectivas'
		]);

        TipoHora::create([
            'descripcion' => 'Reparación Menor'
        ]);

        TipoHora::create([
            'descripcion' => 'Reparación Mayor'
        ]);

        TipoHora::create([
            'descripcion' => 'Mantenimiento'
        ]);

        TipoHora::create([
            'descripcion' => 'Ocio'
        ]);

        TipoHora::create([
            'descripcion' => 'Traslado'
        ]);
	}

}
