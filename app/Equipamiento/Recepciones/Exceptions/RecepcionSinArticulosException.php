<?php

namespace Ghi\Equipamiento\Recepciones\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class RecepcionSinArticulosException extends ReglaNegocioException
{
    protected $message = "La lista de articulos a recibir esta vacia.";
}
