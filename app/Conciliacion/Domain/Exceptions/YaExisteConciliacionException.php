<?php namespace Ghi\Conciliacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class YaExisteConciliacionException extends ReglaNegocioException
{
    protected $message = 'Ya existe una conciliacion que cubre parcial o totalmente este periodo.';
}