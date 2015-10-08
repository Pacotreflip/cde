<?php

namespace Ghi\Equipamiento\Inventarios\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class InventarioYaExisteException extends ReglaNegocioException
{
    protected $message = 'Ya existe un inventario de este articulo.';
}
