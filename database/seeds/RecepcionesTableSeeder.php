<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Recepciones\Recepcion;

class RecepcionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        factory(Recepcion::class, 5)->create()->each(function ($recepcion) use ($faker) {
            $materiales = Material::soloMateriales()->where('tipo_material', 1)->get()->random(5);

            foreach ($materiales as $material) {
                $recepcion->items()->attach($material->id_material, [
                    'cantidad' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000),
                    'precio' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1000)
                ]);
            }
        });
    }
}
