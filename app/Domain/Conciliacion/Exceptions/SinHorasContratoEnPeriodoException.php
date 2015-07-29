<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class SinHorasContratoEnPeriodoException extends ReglaNegocioException
{
    protected $message = 'No existe un registro de horas de contrato vigente para este periodo.';
}
