<?php

use Illuminate\Database\Seeder;
use Ghi\Equipamiento\Adquisiciones\Adquisicion;

class AdquisicionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Adquisicion::class, 20)->create(['id_obra' => 1]);
    }
}
