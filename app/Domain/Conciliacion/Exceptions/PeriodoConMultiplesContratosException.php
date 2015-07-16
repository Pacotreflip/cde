<?php

namespace Ghi\Domain\Conciliacion\Exceptions;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;

class PeriodoConMultiplesContratosException extends ReglaNegocioException
{
    protected $message = 'Existe mas de un contrato en este periodo. Especifique un periodo que cubra un solo contrato.';
}
