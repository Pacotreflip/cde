<?php

namespace Ghi\Domain\Almacenes;

use Laracasts\Presenter\Presenter;

class HoraMensualPresenter extends Presenter
{
    /**
     * @return string
     */
    public function inicio_vigencia()
    {
        return $this->entity->inicio_vigencia->format('Y-m-d');
    }

    /**
     * @return string
     */
    public function inicio_vigencia_local()
    {
        return $this->entity->inicio_vigencia->format('d-m-Y');
    }
}
