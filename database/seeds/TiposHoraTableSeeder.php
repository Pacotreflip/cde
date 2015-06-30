<?php

use Illuminate\Database\Seeder;
use Ghi\Domain\ReportesActividad\TipoHora;

class TiposHoraTableSeeder extends Seeder
{
    public function run()
    {
        factory(TipoHora::class)->create(['descripcion' => 'Efectivas']);
        factory(TipoHora::class)->create(['descripcion' => 'Reparación Menor']);
        factory(TipoHora::class)->create(['descripcion' => 'Reparación Mayor']);
        factory(TipoHora::class)->create(['descripcion' => 'Mantenimiento']);
        factory(TipoHora::class)->create(['descripcion' => 'Ocio']);
        factory(TipoHora::class)->create(['descripcion' => 'Traslado']);
    }
}
