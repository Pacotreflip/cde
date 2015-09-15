<?php

namespace Ghi\Equipamiento\Articulos\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class MaterialConDiferenteTipoException extends ReglaNegocioException
{
    protected $message = 'El tipo de material no coincide con el tipo de la familia';
}