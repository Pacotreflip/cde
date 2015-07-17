<?php

namespace Ghi\Domain\Almacenes;

class Clasificacion
{
    const MAYOR      = 'Mayor';
    const MENOR      = 'Menor';
    const TRANSPORTE = 'Transporte';
    const APOYO      = 'Apoyo';

    protected $clasificacion;

    protected $disponibles = [
        self::MAYOR,
        self::MENOR,
        self::TRANSPORTE,
        self::APOYO,
    ];

    /**
     * Clasificacion constructor.
     *
     * @param $clasificacion
     */
    public function __construct($clasificacion)
    {
        $this->setClasificacion($clasificacion);
    }

    /**
     * @param string $clasificacion
     */
    public function setClasificacion($clasificacion)
    {
        if (is_null($clasificacion) || mb_strlen($clasificacion) == 0) {
            throw new \InvalidArgumentException('clasificacion');
        }

        if (array_key_exists($clasificacion, $this->disponibles)) {
            throw new \InvalidArgumentException("Clasificaición invalida");
        }

        $this->clasificacion = $clasificacion;
    }

    public function __toString()
    {
        return $this->clasificacion;
    }
}
