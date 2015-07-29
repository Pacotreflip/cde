<?php

namespace Ghi\Domain\Conciliacion\Contracts;

use Ghi\Domain\Conciliacion\Conciliacion;
use Illuminate\Support\Collection;

interface CalculadoraPartesUso
{
    /**
     * Calcula la distribucion de los reportes de actividades
     * de una conciliacion en partes de uso
     *
     * @param Conciliacion $conciliacion
     * @return Collection
     */
    public function calcula(Conciliacion $conciliacion);
}
