<?php

use Ghi\Domain\Almacenes\Categoria;
use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    public function run()
    {
        factory(Categoria::class)->create(['descripcion' => 'Equipo Mayor']);
        factory(Categoria::class)->create(['descripcion' => 'Equipo Menor']);
        factory(Categoria::class)->create(['descripcion' => 'Equipo de Transporte']);
    }
}
