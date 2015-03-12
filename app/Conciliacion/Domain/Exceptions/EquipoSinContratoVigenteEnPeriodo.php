<?php namespace Ghi\Conciliacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class EquipoSinContratoVigenteEnPeriodo extends ReglaNegocioException {

    protected $message = 'El equipo no tiene un contrato vigente en el periodo indicado.';
}