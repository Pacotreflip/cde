<?php

namespace Ghi\Domain\Core\Obras;

use Laracasts\Presenter\Presenter;

class ObraPresenter extends Presenter
{
    /**
     * @return mixed
     */
    public function nombrePublico()
    {
        return strlen($this->nombre_publico) ? $this->nombre_publico : $this->nombre;
    }

    /**
     * @return mixed
     */
    public function fechaInicial()
    {
        return $this->fecha_inicial->formatLocalized('%d %B %Y');
    }

    /**
     * @return string
     */
    public function fechaFinal()
    {
        if (is_null($this->fecha_final)) {
            return 'Sin fecha';
        }

        return $this->fecha_final->formatLocalized('%d %B %Y');
    }
}
