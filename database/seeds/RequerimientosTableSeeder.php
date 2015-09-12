<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\Tipo;
use Ghi\Equipamiento\Articulos\Material;

class RequerimientosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = Tipo::all();
        $materiales = Material::soloMateriales()->get();

        foreach ($tipos as $tipo) {
            foreach ($materiales->random(25) as $material) {
                $tipo->materiales()->attach($material->id_material, ['cantidad' => 1]);
            }
        }
    }
}
