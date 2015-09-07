<?php

namespace Ghi\Equipamiento\Articulos;

use Ghi\Core\Exceptions\ReglaNegocioException;

class FamiliaLlenaException extends ReglaNegocioException
{
    protected $message = 'La familia no puede contener mas elementos';
}