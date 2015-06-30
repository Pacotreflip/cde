<?php

namespace Ghi\Domain\ReportesActividad\Events;

class ReporteActividadSeHaRegistrado
{
    /**
     * @var
     */
    public $reporteOperacion;

    /**
     * @param $reporteOperacion
     */
    public function __construct($reporteOperacion)
    {
        $this->reporteOperacion = $reporteOperacion;
    }
}
