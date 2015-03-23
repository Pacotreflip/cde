<?php

use Ghi\Almacenes\Domain\Categoria;
use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder {

    public function run()
    {
        Categoria::create([
            'descripcion' => 'Equipo Mayor'
        ]);

        Categoria::create([
            'descripcion' => 'Equipo Menor'
        ]);

        Categoria::create([
            'descripcion' => 'Equipo de Transporte'
        ]);
    }

}
