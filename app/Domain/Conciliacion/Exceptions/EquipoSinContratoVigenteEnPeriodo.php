<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class EquipoSinContratoVigenteEnPeriodo extends ReglaNegocioException
{
    protected $message = 'El equipo no tiene un contrato vigente en el periodo indicado.';
}
