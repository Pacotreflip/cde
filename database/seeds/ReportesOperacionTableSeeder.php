<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Ghi\Core\Domain\Conceptos\Concepto;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Core\Domain\Usuarios\UserSAO;
use Ghi\Maquinaria\Domain\Operacion\Hora;
use Ghi\Maquinaria\Domain\Operacion\ReporteOperacion;
use Ghi\SharedKernel\Models\Equipo;
use Illuminate\Database\Seeder;

class ReportesOperacionTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		$obra = Obra::firstOrFail();

		$usuariosIds = UserSAO::lists('usuario');

		$conceptosMediblesIds = Concepto::where('id_obra', '=', $obra->id_obra)
			->whereConceptoMedible(3)
			->lists('id_concepto');

		foreach (Equipo::all() as $equipo)
		{
			$inicio = Carbon::now();

			$horometroInicial = $faker->numberBetween(1000, 5500);

			foreach (range(1, rand(10, 120)) as $index)
			{
				$cerrado = $faker->randomElement([true, false]);

				$horometroInicial += rand(4, 24);

				ReporteOperacion::create([
					'id_obra' => $obra->id_obra,
					'id_almacen' => $equipo->id_almacen,
					'fecha' => $inicio->addDay($index)->format('Y-m-d'),
					'horometro_inicial' => $horometroInicial,
					'usuario' => $faker->randomElement($usuariosIds),
					'observaciones' => 'dummy test',
					'cerrado' => $cerrado,
					'horometro_final' => $cerrado ? $horometroInicial + $faker->numberBetween(8, 15) : null,
				]);
			}
		}

		foreach (ReporteOperacion::all() as $reporte)
		{
			$this->creaHora(
				$reporte,
				1,
				$faker->numberBetween(6, 8, 10),
				$faker->sentence(),
				$faker->randomElement($conceptosMediblesIds),
				$faker->randomElement($usuariosIds)
			);

			foreach (range(1, rand(1, 3)) as $index)
			{
				$tipoHora = $faker->numberBetween(2, 5);

				$cantidad = $faker->numberBetween(1, 2);

				$this->creaHora($reporte, $tipoHora, $cantidad, $faker->sentence(), null, $faker->randomElement($usuariosIds));
			}
		}
	}

    /**
     * @param $reporte
     * @param $tipoHora
     * @param $cantidad
     * @param $observaciones
     * @param $idConcepto
     * @param $usuario
     */
    protected function creaHora($reporte, $tipoHora, $cantidad, $observaciones, $idConcepto, $usuario)
	{
		Hora::create([
			'id_reporte' => $reporte->id,
			'id_tipo_hora' => $tipoHora,
			'cantidad' => $cantidad,
			'id_concepto' => $idConcepto,
			'usuario' => $usuario,
			'observaciones' => $observaciones,
		]);
	}

}
