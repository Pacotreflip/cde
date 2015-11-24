<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Areas\AreaTipo;
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
        $tipos = AreaTipo::all();
        $materiales = Material::soloMateriales()->get();

        foreach ($tipos as $tipo) {
            foreach ($materiales->take(10) as $material) {
                $tipo->materialesRequeridos()->create([
                    'id_material' => $material->id_material,
                    'cantidad_requerida' => 1,
                ]);
            }
        }
    }
}
