<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class TipoHoraInvalidoException extends ReglaNegocioException
{
    protected $message = 'EL tipo de hora indicado es invalido';
}
