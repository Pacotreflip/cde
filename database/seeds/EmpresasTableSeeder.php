<?php

use Faker\Factory as Faker;
use Ghi\Maquinaria\Domain\Conciliacion\Models\Proveedor;
use Illuminate\Database\Seeder;

class EmpresasTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('es_ES');

		foreach (range(1, 20) as $index) {
			Proveedor::create([
				'razon_social' => $faker->name,
				'rfc' => '123-123456-er' . $index,
				'tipo_empresa' => $faker->randomElement([1, 2, 8, 4]),
			]);
		}

		foreach (Proveedor::all() as $empresa)
		{
			DB::table('sucursales')->insert([
					'id_empresa' => $empresa->id_empresa,
					'descripcion' => $faker->word,
					'direccion' => $faker->sentence(),
					'ciudad' => $faker->city,
					'estado' => $faker->citySuffix,
					'codigo_postal' => $faker->numerify(),
					'telefono' => $faker->phoneNumber,
					'fax' => $faker->phoneNumber,
					'contacto' => $faker->name,
					'email' => $faker->email,
					'casa_central' => true,
				]
			);
		}

	}

}
