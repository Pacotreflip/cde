<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ReporteYaExisteException extends ReglaNegocioException
{
    protected $message = 'Ya existe un reporte de actividades para la fecha indicada.';
}
