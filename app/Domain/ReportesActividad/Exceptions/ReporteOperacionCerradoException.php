<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ReporteOperacionCerradoException extends ReglaNegocioException
{
    protected $message = 'El reporte de operación ya se encuentra cerrado.';
}
