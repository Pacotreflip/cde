<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class NoExisteOperacionPorConciliarEnPeriodoException extends ReglaNegocioException
{
    protected $message = 'No se encontro operación por conciliar en el periodo indicado.';
}
