<?php namespace Ghi\Operacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class TipoHoraInvalidoException extends ReglaNegocioException {

    protected $message = 'EL tipo de hora indicado es invalido';
}