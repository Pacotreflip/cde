<?php namespace Ghi\Almacenes\Domain;

use Laracasts\Presenter\Presenter;

class AlmacenMaquinariaPresenter extends Presenter {

    /**
     * Descripcion completa de un almacen maquina
     * @return string
     */
    public function descripcionCompleta()
    {
        return $this->numero_economico.' - '.$this->descripcion;
    }

    /**
     * @return string
     */
    public function tipo()
    {
        $tipo = $this->tipo_almacen;

        if ($tipo === AlmacenMaquinaria::TIPO_MAQUINARIA) {
            return 'Almacén de Maquinaria';
        }

        return 'Almacén Maquina (control de insumos)';
    }

}
