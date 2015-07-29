<?php

use Carbon\Carbon;
use Ghi\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Domain\Core\Conceptos\Concepto;
use Ghi\Domain\ReportesActividad\Actividad;
use Ghi\Domain\ReportesActividad\ReporteActividad;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReportesOperacionTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $conceptosMediblesIds = Concepto::where('id_obra', 1)
            ->whereIn('concepto_medible', [Concepto::CONCEPTO_MEDIBLE, Concepto::CONCEPTO_FACTURABLE])
            ->lists('id_concepto')->all();

        foreach (AlmacenMaquinaria::where('id_obra', 1)->where('tipo_almacen', 2)->get() as $almacen) {
            $inicio = Carbon::now();

            $horometroInicial = $faker->numberBetween(1000, 5500);
            $id_concepto = $faker->randomElement($conceptosMediblesIds);

            foreach (range(1, rand(10, 120)) as $index) {
                $horometroInicial += rand(4, 24);

                $reporte = factory(ReporteActividad::class)->create([
                    'id_almacen'        => $almacen->id_almacen,
                    'fecha'             => $inicio->addDay()->format('Y-m-d'),
                    'horometro_inicial' => $horometroInicial,
                    'horometro_final'   => $horometroInicial + $faker->numberBetween(8, 15),
                    'aprobado'          => true,
                ]);

                $reporte->actividades()->save(factory(Actividad::class, 'efectivas')->make([
                    'id_concepto' => $id_concepto
                ]));

                $adicionales = [
                    factory(Actividad::class, 'rep_mayor')->make(),
                    factory(Actividad::class, 'rep_mayor_cargo')->make(),
                    factory(Actividad::class, 'rep_menor')->make(),
                    factory(Actividad::class, 'mantenimiento')->make(),
                    factory(Actividad::class, 'ocio')->make(),
                    factory(Actividad::class, 'traslado')->make(),
                ];

                $reporte->actividades()->save($faker->randomElement($adicionales));
                $reporte->actividades()->save($faker->randomElement($adicionales));
            }
        }
    }
}
