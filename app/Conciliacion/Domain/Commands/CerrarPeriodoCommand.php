<?php namespace Ghi\Conciliacion\Domain\Commands;

class CerrarPeriodoCommand {

    public $id;

    public $horasEfectivas;

    public $horasReparacionMayor;

    public $horasOcio;

    /**
     * @param $id
     * @param $horasEfectivas
     * @param $horasReparacionMayor
     * @param $horasOcio
     */
    function __construct($id, $horasEfectivas, $horasReparacionMayor, $horasOcio)
    {
        $this->id = $id;
        $this->horasEfectivas = $horasEfectivas;
        $this->horasReparacionMayor = $horasReparacionMayor;
        $this->horasOcio = $horasOcio;
    }

}