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
        if ($this->cerrado) {
            return '<span class="label label-success">Cerrado</span>';
        }
    }

    /**
     * Numero de dias del periodo de conciliacion
     *
     * @return string
     */
    public function dias_conciliados()
    {
        $dias = $this->entity->fecha_inicial->diffInDays($this->entity->fecha_final) + 1;

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
    public function horas_contrato()
    {
        return number_format($this->entity->horas_contrato, 0);
    }

    /**
     * @return string
     */
    public function horasConciliadasConUnidad()
    {
        $horas = $this->horas_conciliadas();

        $plural = str_plural('Hr', $horas);

        return $horas . ' ' . $plural;
    }

    /**
     * @return float
     */
    public function horas_conciliadas()
    {
        return round($this->entity->horas_conciliadas);
    }

    /**
     * @return string
     */
    public function horas_efectivas()
    {
        return number_format($this->entity->horas_efectivas, 0);
    }

    /**
     * @return string
     */
    public function horas_reparacion_mayor()
    {
        return number_format($this->entity->horas_reparacion_mayor, 0);
    }

    /**
     * @return string
     */
    public function horas_reparacion_menor()
    {
        return number_format($this->entity->horas_reparacion_menor, 0);
    }

    /**
     * @return string
     */
    public function horas_mantenimiento()
    {
        return number_format($this->entity->horas_mantenimiento, 0);
    }

    /**
     * @return string
     */
    public function horas_ocio()
    {
        return number_format($this->entity->horas_ocio, 0);
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

    /**
     * @return string
     */
    public function horometro_inicial()
    {
        return number_format($this->entity->horometro_inicial, 0);
    }

    /**
     * @return string
     */
    public function horometro_final()
    {
        return number_format($this->entity->horometro_final, 0);
    }

    /**
     * @return string
     */
    public function horas_horometro()
    {
        return number_format($this->entity->horas_horometro, 0);
    }

    /**
     * @return string
     */
    public function horas_a_conciliar()
    {
        return number_format($this->horas_a_conciliar, 0);
    }
}
