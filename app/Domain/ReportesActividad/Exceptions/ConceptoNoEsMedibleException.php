<?php

namespace Ghi\Domain\ReportesActividad\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class ConceptoNoEsMedibleException extends ReglaNegocioException
{
    protected $message = 'El concepto especificado no es medible.';
}
