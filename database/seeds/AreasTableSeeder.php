<?php

use Ghi\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proyecto = factory(Area::class)->create([
            'nombre' => 'HOTEL SECRETS PLAYA MUJERES',
            'lft' => 1,
            'rgt' => 2
        ]);

        $edificio1 = factory(Area::class)->make(['nombre' => 'Edificio 1']);
        $proyecto->insertNode($edificio1);
        $jrsuites = $edificio1->insertNode(factory(Area::class)->make(['nombre' => 'Junior Suites']));
        $edificio1->insertNode(factory(Area::class)->make(['nombre' => 'Master Suites']));
        $jrsuites->insertNode(factory(Area::class)->make(['nombre' => 'Habitacion 1']));
        $jrsuites->insertNode(factory(Area::class)->make(['nombre' => 'Habitacion 2']));
        $jrsuites->insertNode(factory(Area::class)->make(['nombre' => 'Habitacion 3']));
        $jrsuites->insertNode(factory(Area::class)->make(['nombre' => 'Habitacion 4']));
        $proyecto->insertNode(factory(Area::class)->create(['nombre' => 'Edificio 2']));
        $proyecto->insertNode(factory(Area::class)->create(['nombre' => 'Edificio 3']));
        $proyecto->insertNode(factory(Area::class)->create(['nombre' => 'Edificio 4']));
//        $proyecto->insertNode(factory(Area::class)->create(['nombre' => 'Edificio 5']));
//        $proyecto->insertNode(factory(Area::class)->create(['nombre' => 'Edificio 6']));
    }
}
