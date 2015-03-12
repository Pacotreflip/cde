<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Ghi\Core\Domain\Almacenes\Material;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Maquinaria\Domain\Conciliacion\Models\ContratoRenta;
use Ghi\Maquinaria\Domain\Conciliacion\Models\OrdenRenta;
use Ghi\Maquinaria\Domain\Conciliacion\Models\Proveedor;
use Ghi\SharedKernel\Models\Equipo;
use Illuminate\Database\Seeder;

class RentasTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create('es_ES');

		$obra = Obra::firstOrFail();

		$this->seedOrdenesRenta($obra, $faker);

		$this->seedEntradasEquipo($faker);
	}

	/**
	 * @param $obra
	 * @param $faker
	 * @param int $cuantos
	 */
	protected function seedOrdenesRenta($obra, $faker, $cuantos = 40)
	{
		$empresasIds = Proveedor::whereTipoEmpresa(1)->lists('id_empresa');

		$materialesIds = Material::whereTipoMaterial(8)
			->whereRaw('LEN(nivel) = 8')
			->lists('id_material');

		foreach (range(1, $cuantos) as $id)
		{
			$fecha = Carbon::now()->addDays(rand(1, 10));

			OrdenRenta::create([
				'id_obra' => $obra->id_obra,
				'tipo_transaccion' => 19,
				'opciones' => 8,
				'fecha' => $fecha->format('Y-m-d'),
				'cumplimiento' => $fecha->addDay()->format('Y-m-d'),
				'id_empresa' => $faker->randomElement($empresasIds),
				'id_sucursal' => 1,
				'id_moneda' => 1,
				'saldo' => 0,
				'comentario' => $faker->sentence(),
				'observaciones' => $faker->sentence(),
			]);
		}

		foreach (OrdenRenta::all() as $orden)
		{
			$descuento = $faker->randomElement([0, 5, 10, 50]);
			$precioUnitario = $faker->numberBetween(30, 2010) * (1 + ($descuento / 100));
//			$cantidad = $faker->randomElement([200, 250, 400, 450, 480, 600, 800, 900, 1500, 1600, 2000]);
			$cantidad = $faker->randomElement([200, 250, 400, 450, 480, 600]);
			$importe = $cantidad * $precioUnitario;
			$anticipo = $faker->randomElement([0, 10, 50, 100]);
			$iva = $faker->randomElement([0, $obra->iva]);

			$item = ContratoRenta::create([
				'id_transaccion' => $orden->id_transaccion,
				'id_material' => $faker->randomElement($materialesIds),
				'unidad' => 'HORA',
				'numero' => 1,
				'cantidad' => $cantidad,
				'importe' => $importe,
				'precio_unitario' => $precioUnitario,
				'precio_material' => $precioUnitario,
				'anticipo' => $anticipo,
				'descuento' => $descuento,
			]);

			DB::table('dbo.entregas')->insert([
				'id_item' => $item->id_item,
				'numero_entrega' => 1,
				'fecha' => $orden->fecha,
				'cantidad' => $item->cantidad,
				'pedidas' => $item->numero
			]);

			$orden->monto = $importe * (1 + ($iva / 100));
			$orden->saldo = $orden->monto;
			$orden->impuesto = $importe * ($iva / 100);
			$orden->anticipo_monto = $orden->monto * ($anticipo / 100);
			$orden->save();
		}
	}

	/**
	 * @param $faker
	 */
	protected function seedEntradasEquipo($faker)
	{
		foreach (OrdenRenta::all() as $orden)
		{
			$fecha = $orden->fecha->addDays(rand(1, 10));

			$cumplimiento = $fecha->format('Y-m-d');

			$vencimiento = $faker->randomElement([
				null,
				$orden->fecha->addDays(rand(30, 120, 90))->format('Y-m-d')
			]);

			$idEntrada = DB::table('dbo.transacciones')->insertGetId([
				'id_antecedente' => $orden->id_transaccion,
				'tipo_transaccion' => 33,
				'opciones' => 8,
				'fecha' => $fecha->format('Y-m-d'),
				'id_obra' => $orden->id_obra,
				'id_empresa' => $orden->id_empresa,
				'id_sucursal' => $orden->id_sucursal,
				'id_moneda' => $orden->id_moneda,
				'cumplimiento' => $cumplimiento,
				'vencimiento' => $vencimiento,
				'referencia' => $faker->word . $faker->postcode,
				'comentario' => $faker->sentence(),
				'observaciones' => $faker->sentence(),
			]);

			foreach ($orden->items as $key => $item)
			{
				// almacenes que aun no tienen entrada de equipo
				$almacenIds = Equipo::has('maquinas', '=', 0)
					->where('id_material', '=', $item->id_material)
					->lists('id_almacen');

				if ( ! $almacenIds) continue;

				$idAlmacen = $faker->randomElement($almacenIds);

				$numeroSerie = $faker->postcode;

				$id_item = DB::table('dbo.items')->insertGetId([
					'id_transaccion' => $idEntrada,
					'id_antecedente' => $orden->id_transaccion,
					'item_antecedente' => $item->id_item,
					'id_almacen' => $idAlmacen,
					'id_material' => $item->id_material,
					'unidad' => $item->unidad,
					'importe' => $item->importe,
					'saldo' => $item->importe,
					'precio_unitario' => $item->precio_unitario,
					'anticipo' => $item->anticipo,
					'referencia' => $numeroSerie,
				]);

				DB::table('dbo.inventarios')->insert([
					'id_almacen' => $idAlmacen,
					'id_material' => $item->id_material,
					'id_item' => $id_item,
					'fecha_desde' => $cumplimiento,
					'fecha_hasta' => $vencimiento,
					'referencia' => $numeroSerie,
				]);
			}
		}
	}

}
