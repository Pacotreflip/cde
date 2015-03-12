<?php namespace Ghi\Core\Domain\Conceptos;

use Laracasts\Presenter\Presenter;

class ConceptoPresenter extends Presenter {

    /**
     * Muestra la descripcion de un concepto con su clave
     * @return mixed|string
     */
    public function descripcionConClave()
    {
        if ($this->clave_concepto)
        {
            return '[' . $this->clave_concepto . ']' . ' ' . $this->descripcion;
        }

        return $this->descripcion;
    }
}