<?php namespace Ghi\Operacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class ReporteDeOperacionYaExisteException extends ReglaNegocioException {

    protected $message = 'Ya existe un reporte de operacion para la fecha indicada.';
}