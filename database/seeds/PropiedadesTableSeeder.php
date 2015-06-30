<?php

use Ghi\Domain\Almacenes\Propiedad;
use Illuminate\Database\Seeder;

class PropiedadesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Propiedad::class)->create(['descripcion' => 'Propio']);
        factory(Propiedad::class)->create(['descripcion' => 'Rentado']);
        factory(Propiedad::class)->create(['descripcion' => 'En Sociedad']);
    }
}
