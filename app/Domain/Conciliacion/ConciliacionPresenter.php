<?php

namespace Ghi\Domain\Conciliacion;

use Laracasts\Presenter\Presenter;

class ConciliacionPresenter extends Presenter
{
    /**
     * Descripcion del periodo de conciliacion con formato
     *
     * @return string
     */
    public function periodo()
    {
        $inicio = ucwords($this->fecha_inicial->formatLocalized('%d %B %Y'));
        $termino = ucwords($this->fecha_final->formatLocalized('%d %B %Y'));

        return "{$inicio} al {$termino}";
    }

    /**
     * @return string
     */
    public function statusLabel()
    {
        if ($this->aprobada) {
            return '<span class="label label-success">APROBADA</span>';
        }
    }

    /**
     * Numero de dias del periodo de conciliacion
     *
     * @return string
     */
    public function dias_conciliados()
    {
        $dias = $this->entity->diasConciliados();

        $plural = str_plural('dia', $dias);

        return $dias . ' ' . $plural;
    }

    /**
     * @return string
     */
    public function dias_con_operacion()
    {
        $dias = $this->entity->dias_con_operacion;

        $plural = str_plural('dia', $dias);

        return $dias . ' ' . $plural;
    }

    /**
     * @return string
     */
    public function suma_horas()
    {
        $horas = $this->horas_efectivas;
        $horas += $this->horas_reparacion_mayor;
        $horas += $this->horas_reparacion_menor;
        $horas += $this->horas_mantenimiento;
        $horas += $this->horas_ocio;

        return number_format($horas, 0);
    }
}
