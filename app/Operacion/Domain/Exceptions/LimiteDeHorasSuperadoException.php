<?php namespace Ghi\Operacion\Domain\Exceptions;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

class LimiteDeHorasSuperadoException extends ReglaNegocioException {

    protected $horasActuales;

    protected $message = 'El numero de horas para un reporte no puede superar las 24 horas.';

    public function setHorasActuales($horasActuales)
    {
        $this->message .= " Este reporte tiene un total de {$horasActuales}.";

        return $this;
    }
}