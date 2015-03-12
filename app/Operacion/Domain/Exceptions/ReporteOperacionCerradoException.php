<?php namespace Ghi\Operacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class ReporteOperacionCerradoException extends ReglaNegocioException {

    protected $message = 'El reporte de operación ya se encuentra cerrado.';
}