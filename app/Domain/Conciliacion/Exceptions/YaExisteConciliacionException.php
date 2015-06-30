<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class YaExisteConciliacionException extends ReglaNegocioException
{
    protected $message = 'Ya existe una conciliacion que cubre parcial o totalmente este periodo.';
}
