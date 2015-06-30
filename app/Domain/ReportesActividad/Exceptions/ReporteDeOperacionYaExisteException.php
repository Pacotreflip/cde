<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ReporteDeOperacionYaExisteException extends ReglaNegocioException
{
    protected $message = 'Ya existe un reporte de operacion para la fecha indicada.';
}
