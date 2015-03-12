<?php namespace Ghi\Conciliacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class NoExisteOperacionPorConciliarEnPeriodoException extends ReglaNegocioException {

    protected $message = 'No se encontro operación por conciliar en el periodo indicado.';
}