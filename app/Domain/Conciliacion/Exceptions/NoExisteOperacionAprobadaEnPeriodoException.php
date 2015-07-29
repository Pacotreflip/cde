<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class NoExisteOperacionAprobadaEnPeriodoException extends ReglaNegocioException
{
    protected $message = 'No se encontraron reportes de actividad aprobados en el periodo indicado.';
}
