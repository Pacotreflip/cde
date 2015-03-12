<?php namespace Ghi\Core\Domain\Almacenes;

use Laracasts\Presenter\Presenter;

class AlmacenPresenter extends Presenter {

    /**
     * Descripcion completa de un almacen maquina
     * @return string
     */
    public function descripcionCompleta()
    {
        return $this->numero_economico.' - '.$this->descripcion;
    }
} 