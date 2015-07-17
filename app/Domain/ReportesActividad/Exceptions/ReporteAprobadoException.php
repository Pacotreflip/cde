<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ReporteAprobadoException extends ReglaNegocioException
{
    protected $message = 'Este reporte no puede ser modificado por que ya esta aprobado.';
}
