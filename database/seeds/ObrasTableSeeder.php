<?php

use Ghi\Core\Domain\Obras\Obra;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ObrasTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create('es_ES');

        Obra::create([
            'nombre' => 'OBRA ' . $faker->company,
            'tipo_obra' => 1,
            'constructora' => $faker->company,
            'fecha_inicial' => $faker->dateTimeThisYear,
            'fecha_final' => $faker->dateTimeAD,
            'iva' => 16,
            'id_moneda' => 1,
        ]);
    }

}