<?php

namespace Ghi\Equipamiento\Inventarios\Exceptions;

use Ghi\Core\Exceptions\ReglaNegocioException;

class SinExistenciaSuficienteException extends ReglaNegocioException
{
    protected $message = 'No hay existencias suficientes en el inventario para hacer la disminución requerida, verifique que los artículos no se encuentren asignados a alguna área.';
}
