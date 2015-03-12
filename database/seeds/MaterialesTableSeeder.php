<?php

use Faker\Factory as Faker;
use Ghi\Core\Domain\Almacenes\Material;
use Ghi\SharedKernel\Models\Unidad;
use Illuminate\Database\Seeder;

class MaterialesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('es_ES');

		$unidadesIds = Unidad::lists('unidad');

		$this->seedTipoMaquinaria($faker, $unidadesIds);
	}

	/**
	 * @param $faker
	 * @param $unidadesIds
	 * @param int $cuantos
	 */
	protected function seedTipoMaquinaria($faker, $unidadesIds, $cuantos = 50)
	{
		$material = Material::create([
			'descripcion' => $faker->sentence,
			'nivel' => '000.',
			'tipo_material' => '8',
		]);

		foreach (range(1, $cuantos) as $index)
		{
			Material::create([
				'descripcion' => $faker->sentence,
				'nivel' => "000." . str_repeat('0', 3 - strlen($index)) . $index . '.',
				'tipo_material' => $material->tipo_material,
				'unidad' => $faker->randomElement($unidadesIds),
				'marca' => 1,
			]);
		}
	}

}
