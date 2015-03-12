<?php namespace Ghi\Conciliacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class PeriodoYaFueConciliadoException extends ReglaNegocioException {

    protected $message = 'El periodo indicado ya fue conciliado total o parcialmente.';
}