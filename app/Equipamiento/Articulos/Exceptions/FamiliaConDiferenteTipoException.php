<?php

namespace Ghi\Equipamiento\Articulos\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class FamiliaConDiferenteTipoException extends ReglaNegocioException
{
    protected $message = 'El tipo de familia no coincide con el tipo del material';
}