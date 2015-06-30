<?php namespace Ghi\Conciliacion\Domain\Commands;

use Carbon\Carbon;

class GenerarPeriodoCommand {

    public $idObra;

    public $idProveedor;

    public $idEquipo;

    public $fechaInicial;

    public $fechaFinal;

    public $observaciones;

    public $usuario;

    /**
     * @param $idObra
     * @param $idProveedor
     * @param $idEquipo
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $observaciones
     * @param $usuario
     */
    function __construct($idObra, $idProveedor, $idEquipo, $fechaInicial, $fechaFinal, $observaciones, $usuario)
    {
        $this->idObra = $idObra;
        $this->idProveedor = $idProveedor;
        $this->idEquipo = $idEquipo;
        $this->fechaInicial = new Carbon($fechaInicial);
        $this->fechaFinal = new Carbon($fechaFinal);
        $this->observaciones = $observaciones;
        $this->usuario = $usuario;
    }
}