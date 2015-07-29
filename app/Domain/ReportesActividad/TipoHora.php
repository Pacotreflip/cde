<?php

namespace Ghi\Domain\ReportesActividad;

class TipoHora
{
    const EFECTIVA         = 'EF';
    const REPARACION_MAYOR = 'RM';
    const REPARACION_MENOR = 'Rm';
    const MANTENIMIENTO    = 'MT';
    const OCIO             = 'OC';
    const TRASLADO         = 'TR';

    /**
     * @var
     */
    private $codigo;

    /**
     * @var array
     */
    private $codigosDisponibles = [
        self::EFECTIVA         => 'Efectivas',
        self::REPARACION_MAYOR => 'Reparación Mayor',
        self::REPARACION_MENOR => 'Reparación Menor',
        self::MANTENIMIENTO    => 'Mantenimiento',
        self::OCIO             => 'Ocio',
        self::TRASLADO         => 'Traslado',
    ];

    /**
     * @param $codigo
     */
    public function __construct($codigo)
    {
        $this->setCodigo($codigo);
    }

    /**
     * @param $codigo
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function setCodigo($codigo)
    {
        if (is_null($codigo) || mb_strlen($codigo) == 0) {
            throw new \InvalidArgumentException('codigo');
        }

        if (! array_key_exists($codigo, $this->codigosDisponibles)) {
            throw new \InvalidArgumentException('El tipo de hora no es válido');
        }

        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescripcion()
    {
        return $this->codigosDisponibles[$this->codigo];
    }

    public function __toString()
    {
        return $this->getDescripcion();
    }
}
