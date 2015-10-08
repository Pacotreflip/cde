<?php

namespace Ghi\Equipamiento\Inventarios\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class SinExistenciaSuficienteException extends ReglaNegocioException
{
    protected $message = 'No hay existencia disponible';
}
