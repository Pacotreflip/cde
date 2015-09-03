<?php

use Illuminate\Database\Seeder;

class ClasificadoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Ghi\Equipamiento\Articulos\Clasificador::class, 5)->create();
    }
}
