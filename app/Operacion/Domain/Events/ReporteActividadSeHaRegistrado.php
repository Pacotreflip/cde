<?php namespace Ghi\Operacion\Domain\Events;

class ReporteActividadSeHaRegistrado {

    /**
     * @var
     */
    public $reporteOperacion;

    /**
     * @param $reporteOperacion
     */
    function __construct($reporteOperacion)
    {
        $this->reporteOperacion = $reporteOperacion;
    }


} 