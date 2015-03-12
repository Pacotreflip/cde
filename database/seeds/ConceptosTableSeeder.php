<?php

use Faker\Factory as Faker;
use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Obras\Obra;
use Illuminate\Database\Seeder;

class ConceptosTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('es_ES');

		$obra = Obra::first();

		$this->generaNiveles($faker, $obra, 7);
	}

	/**
	 * @param $faker
	 * @param Obra $obra
	 * @param $profundidad
	 * @param string $nivelAncestro
	 */
	private function generaNiveles($faker, Obra $obra, $profundidad, $nivelAncestro = '')
	{
		$faker = Faker::create();

		$cuantos = rand(3, 9);

		$conceptoMedible = 0;

		foreach (range(1, $cuantos) as $index)
		{
			if( $profundidad == 0) break;

			$nivel = $nivelAncestro . "00{$index}.";

			// cuando sea el ultimo nivel de la jerarquia actual
			// se generaran como conceptos medibles
			if ( $profundidad == 1 && $profundidad == $index)
			{
				$conceptoMedible = 3;
			}

			Concepto::create([
				'id_obra' => $obra->id_obra,
				'clave_concepto' => $faker->postcode,
				'descripcion' => $faker->sentence(),
				'nivel' => $nivel,
				'concepto_medible' => $conceptoMedible,
			]);

			// si ya son medibles se generan todos los aleatorios
			if ($conceptoMedible == 3) continue;

			$this->generaNiveles($faker, $obra, --$profundidad, $nivel);
		}
	}

}
