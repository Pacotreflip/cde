<?php

namespace Ghi\Equipamiento\Inventarios\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class InventarioNoEncontradoException extends ReglaNegocioException
{
    protected $message = 'No hay inventario de este articulo.';
}
