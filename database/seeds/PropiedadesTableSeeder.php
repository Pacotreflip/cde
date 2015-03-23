<?php

use Ghi\Almacenes\Domain\Propiedad;
use Illuminate\Database\Seeder;

class PropiedadesTableSeeder extends Seeder {

    public function run()
    {
        Propiedad::create([
            'descripcion' => 'Propio',
        ]);

        Propiedad::create([
            'descripcion' => 'Rentado',
        ]);

        Propiedad::create([
            'descripcion' => 'En Sociedad',
        ]);
    }

}
