<?php

use Faker\Factory as Faker;
use Ghi\Core\Domain\Almacenes\Material;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\SharedKernel\Models\Equipo;
use Illuminate\Database\Seeder;

class AlmacenesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('es_ES');

		$obra = Obra::firstOrFail();

		$this->seedTipoMaquinaria($obra, $faker, 30);
	}

	/**
	 * @param $obra
	 * @param $faker
	 * @param int $cuantos
	 */
	protected function seedTipoMaquinaria($obra, $faker, $cuantos = 15)
	{
		$materiales = Material::whereTipoMaterial(8)
			->whereRaw('LEN(nivel) = 8')
			->get()
			->toArray();

		foreach (range(1, $cuantos) as $index)
		{
			$material = $faker->randomElement($materiales);

			Equipo::create([
				'id_obra' => $obra->id_obra,
				'descripcion' => 'EQUIPO - ' . $material['descripcion'] . " {$index}",
				'tipo_almacen' => 2,
				'id_material' => $material['id_material'],
			]);
		}
	}

}
