<?php

use Faker\Factory as Faker;
use Ghi\SharedKernel\Models\Unidad;
use Illuminate\Database\Seeder;

class UnidadesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Unidad::create([
			'unidad' => 'HORA',
			'tipo_unidad' => 6,
			'descripcion' => 'HORA',
		]);
	}

}
