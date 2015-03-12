<?php namespace Ghi\Operacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class ConceptoNoEsMedibleException extends ReglaNegocioException {

    protected $message = 'El concepto especificado no es medible.';
}