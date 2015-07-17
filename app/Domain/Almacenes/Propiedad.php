<?php

namespace Ghi\Domain\Almacenes;

class Propiedad
{
    const PROPIO   = 'Propio';
    const TERCEROS = 'Terceros';
    const SOCIEDAD = 'Sociedad';

    protected $descripcion;

    protected $disponibles = [
        self::PROPIO   => self::PROPIO,
        self::TERCEROS => self::TERCEROS,
        self::SOCIEDAD => self::SOCIEDAD,
    ];

    /**
     * Propiedad constructor.
     *
     * @param string $descripcion
     */
    public function __construct($descripcion)
    {
        $this->setDescripcion($descripcion);
    }

    /**
     * @param string $descripcion
     */
    protected function setDescripcion($descripcion)
    {
        if (is_null($descripcion) || mb_strlen($descripcion) == 0) {
            throw new \InvalidArgumentException("descripcion");
        }

        if (! array_key_exists($descripcion, $this->disponibles)) {
            throw new \InvalidArgumentException("La propiedad es invalida");
        }

        $this->descripcion = $descripcion;
    }

    public function __toString()
    {
        return $this->descripcion;
    }
}
