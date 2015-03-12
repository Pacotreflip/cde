<?php namespace Ghi\Conciliacion\Domain\Periodos;

use Laracasts\Presenter\Presenter;

class PeriodoConciliacionPresenter extends Presenter {

    /**
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
        if ($this->cerrado)
        {
            return '<span class="label label-success">Cerrado</span>';
        }
    }

    /**
     * @return string
     */
    public function diasConciliacion()
    {
        $dias = $this->fecha_inicial->diffInDays($this->fecha_final) + 1;

        $plural = str_plural('dia', $dias);

        return $dias . ' ' . $plural;
    }

    /**
     * @return string
     */
    public function diasConOperacion()
    {
        $dias = $this->dias_con_operacion;

        $plural = str_plural('dia', $dias);

        return $dias . ' ' . $plural;
    }

    /**
     * @return string
     */
    public function horasContrato()
    {
        return number_format($this->horas_contrato, 0);
    }

    /**
     * @return string
     */
    public function horasConciliadasConUnidad()
    {
        $horas = $this->horasConciliadas();

        $plural = str_plural('Hr', $horas);

        return $horas . ' ' . $plural;
    }

    /**
     * @return float
     */
    public function horasConciliadas()
    {
        return round($this->horas_conciliadas);
    }

    /**
     * @return string
     */
    public function horasEfectivas()
    {
        return number_format($this->horas_efectivas, 0);
    }

    /**
     * @return string
     */
    public function horasReparacionMayor()
    {
        return number_format($this->horas_reparacion_mayor, 0);
    }

    /**
     * @return string
     */
    public function horasReparacionMenor()
    {
        return number_format($this->horas_reparacion_menor, 0);
    }

    /**
     * @return string
     */
    public function horasMantenimiento()
    {
        return number_format($this->horas_mantenimiento, 0);
    }

    /**
     * @return string
     */
    public function horasOcio()
    {
        return number_format($this->horas_ocio, 0);
    }

    /**
     * @return string
     */
    public function totalHoras()
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
    public function horometroInicial()
    {
        return number_format($this->horometro_inicial, 0);
    }

    /**
     * @return string
     */
    public function horometroFinal()
    {
        return number_format($this->horometro_final, 0);
    }

    /**
     * @return string
     */
    public function horasHorometro()
    {
        return number_format($this->horas_horometro, 0);
    }

    /**
     * @return string
     */
    public function horasAConciliar()
    {
        return number_format($this->horas_a_conciliar, 0);
    }
}