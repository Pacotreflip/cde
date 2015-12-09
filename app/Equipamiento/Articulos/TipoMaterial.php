<?php

namespace Ghi\Equipamiento\Articulos;

class TipoMaterial
{
    const TIPO_MATERIALES            = 1;
    const TIPO_MANO_OBRA             = 2;
    const TIPO_SERVICIOS             = 3;
    const TIPO_HERRAMIENTA_Y_EQUIPO  = 4;
    const TIPO_MAQUINARIA            = 8;

    /**
     * @var int
     */
    protected $tipo;

    /**
     * Tipos de material disponibles
     *
     * @var array
     */
    protected $tipos_disponibles = [
       self::TIPO_MATERIALES            => 'MATERIALES',
       self::TIPO_MANO_OBRA             => 'MANO DE OBRA',
       self::TIPO_SERVICIOS             => 'SERVICIOS',
       self::TIPO_MAQUINARIA            => 'MAQUINARIA',
       self::TIPO_HERRAMIENTA_Y_EQUIPO  => 'HERRAMIENTA Y EQUIPO',
    ];

    /**
     *
     * @param int $tipo
     */
    public function __construct($tipo)
    {
        $this->setTipo($tipo);
    }

    /**
     * Establece el valor para este tipo de material
     *
     * @param int $tipo
     * @throws \InvalidArgumentException
     */
    protected function setTipo($tipo)
    {
        $tipo = \filter_var($tipo, FILTER_VALIDATE_INT);

        if ($tipo === false) {
            throw new \InvalidArgumentException('tipo');
        }

        if (! array_key_exists($tipo, $this->tipos_disponibles)) {
            throw new \InvalidArgumentException('El tipo de material no es válido');
        }

        $this->tipo = $tipo;
    }

    /**
     * Obtiene la descripcion del tipo
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->tipos_disponibles[$this->tipo];
    }

    /**
     * Obtiene el valor de este tipo
     *
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Obtiene el valor real de este tipo para ser almacenado
     *
     * @return string
     */
    public function getTipoReal()
    {
        if ($this->tipo == self::TIPO_SERVICIOS) {
            return 2;
        }

        return $this->tipo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tipo;
    }
}
