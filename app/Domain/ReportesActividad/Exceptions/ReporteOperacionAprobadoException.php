<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ReporteOperacionAprobadoException extends ReglaNegocioException
{
    protected $message = 'Este reporte no puede ser modificado por que ya esta aprobado.';
}
