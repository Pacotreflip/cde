<?php namespace Ghi\Operacion\Domain;

use Illuminate\Support\Str;
use Laracasts\Presenter\Presenter;

class ReporteActividadPresenter extends Presenter {

    /**
     * Presenta la suma de horas del reporte con el sufijo Hr u Hrs
     * @return string
     */
    public function sumaHoras()
    {
        $count = $this->actividades->sum('cantidad');

        $plural = Str::plural('Hr', $count);

        return $this->actividades->sum('cantidad') . " " . $plural;
    }

    /**
     * Devuelve la fecha del reporte en formato A-m-d
     * @return mixed
     */
    public function fechaFormatoLocal()
    {
        return ucwords($this->entity->fecha->formatLocalized('%d %B %Y'));
    }

    /**
     * Presenta la fecha del reporte en formato d-m-a
     *
     * @return mixed
     */
    public function fecha()
    {
        return $this->entity->fecha->format('d-m-Y');
    }

    /**
     * Devuelve la etiqueta del estado actual del documento
     *
     * @return string
     */
    public function estatusLabel()
    {
        if ($this->entity->cerrado)
        {
            return 'CERRADO';
        }

        return 'PENDIENTE';
    }
} 