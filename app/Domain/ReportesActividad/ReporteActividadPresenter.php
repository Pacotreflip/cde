<?php

namespace Ghi\Domain\ReportesActividad;

use Laracasts\Presenter\Presenter;

class ReporteActividadPresenter extends Presenter
{
    /**
     * Presenta la suma de horas del reporte con el sufijo Hr u Hrs
     *
     * @return string
     */
    public function sumaHoras()
    {
        $count = $this->actividades->sum('cantidad');
        $plural = str_plural('Hr', $count);

        return $this->actividades->sum('cantidad') . " " . $plural;
    }

    /**
     * Devuelve la fecha del reporte en formato A-m-d
     *
     * @return string
     */
    public function fechaFormatoLocal()
    {
        return ucwords($this->entity->fecha->formatLocalized('%d %B %Y'));
    }

    /**
     * Presenta la fecha del reporte en formato d-m-a
     *
     * @return string
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
    public function textoEstado()
    {
        if ($this->aprobado) {
            return 'Aprobado';
        }
        if ($this->conciliado) {
            return 'Conciliado';
        }

        return 'Capturado';
    }
}
