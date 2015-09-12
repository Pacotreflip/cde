<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Articulos\Clasificador;

class ClasificadoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Clasificador::class, 5)->create()->each(function ($nivel1) {
            factory(Clasificador::class, 5)->create(['parent_id' => $nivel1->id])->each(function ($nivel2) {
                factory(Clasificador::class, 5)->create(['parent_id' => $nivel2->id]);
            });
        });
    }
}
