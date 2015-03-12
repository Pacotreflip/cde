<?php namespace Ghi\Core\Domain\Almacenes;

class RegistroAlmacenCommand {

    /**
     * @var string
     */
    public $descripcion;

    /**
     * @var string
     */
    public $economico;

    /**
     * @var string
     */
    public $material;

    /**
     * @var string
     */
    public $propiedad;

    /**
     * @var string
     */
    public $categoria;

    /**
     * @param $descripcion
     * @param $economico
     * @param $categoria
     * @param $material
     * @param $propiedad
     */
    function __construct($descripcion, $economico, $categoria, $material, $propiedad)
    {
        $this->descripcion = $descripcion;
        $this->economico = $economico;
        $this->categoria = $categoria;
        $this->material = $material;
        $this->propiedad = $propiedad;
    }

}