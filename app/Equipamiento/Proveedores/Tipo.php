<?php

namespace Ghi\Equipamiento\Proveedores;

class Tipo
{
    const PROVEEDOR = 0;
    const PROVEEDOR_MATERIALES = 1;
    const CONTRATISTA = 2;
    const PROVEEDOR_MATERIALES_CONTRATISTA = 3;

    /**
     * @var int
     */
    protected $tipo;

    /**
     * Tipos de proveedor disponibles
     * @var array
     */
    protected $tipos_disponibles = [
        self::PROVEEDOR => 'Proveedor',
        self::PROVEEDOR_MATERIALES => 'Proveedor de Materiales',
        self::CONTRATISTA => 'Contratista',
        self::PROVEEDOR_MATERIALES_CONTRATISTA => 'Proveedor de Materiales y Contratista',
    ];

    /**
     * Crea un tipo de proveedor
     * 
     * @param int $tipo
     */
    public function __construct($tipo)
    {
        $this->setTipo($tipo);
    }

    /**
     * Establece el tipo de proveedor
     * 
     * @param int $tipo
     */
    protected function setTipo($tipo)
    {
        $tipo = \filter_var($tipo, FILTER_VALIDATE_INT);

        if ($tipo === false) {
            throw new \InvalidArgumentException('tipo');
        }

        if (! array_key_exists($tipo, $this->tipos_disponibles)) {
            throw new \InvalidArgumentException('El tipo de proveedor no es válido');
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->tipo;
    }
}
