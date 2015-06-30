<?php

namespace Ghi\Domain\ReportesActividad;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class ActividadPresenter extends Presenter
{
    /**
     * Da formato a la hora incial de la actividad
     *
     * @return mixed
     */
    public function horaInicial()
    {
        if (is_null($this->entity->hora_inicial)) {
            return '-:-:-';
        }

        $hora = new Carbon($this->entity->hora_inicial);

        return $hora->format('h:i:s A');
    }

    /**
     * Da formato a la hora final de la actividad
     * @return string
     */
    public function horaFinal()
    {
        if (is_null($this->entity->hora_final)) {
            return '-:-:-';
        }

        $hora = new Carbon($this->entity->hora_final);

        return $hora->format('h:i:s A');
    }
}
