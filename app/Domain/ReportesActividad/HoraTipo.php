<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\ReportesActividad\Exceptions\TipoHoraInvalidoException;

class HoraTipo
{
    const EFECTIVA = 1;
    const REPARACION_MENOR = 2;
    const REPARACION_MAYOR = 3;
    const MANTENIMIENTO = 4;
    const OCIO = 5;

    private $idTipoHora;

    /**
     * @param $idTipoHora
     * @throws TipoHoraInvalidoException
     */
    public function __construct($idTipoHora)
    {
        $this->validaIdTipoHora($idTipoHora);

        $this->idTipoHora = $idTipoHora;
    }

    /**
     * @param $idTipoHora
     * @throws TipoHoraInvalidoException
     */
    protected function validaIdTipoHora($idTipoHora)
    {
        if (
            $idTipoHora != static::EFECTIVA &&
            $idTipoHora != static::REPARACION_MAYOR &&
            $idTipoHora != static::REPARACION_MENOR &&
            $idTipoHora != static::MANTENIMIENTO &&
            $idTipoHora != static::OCIO
        ) {
            throw new TipoHoraInvalidoException;
        }
    }

    /**
     * @return mixed
     */
    public function getIdTipoHora()
    {
        return $this->idTipoHora;
    }
}
