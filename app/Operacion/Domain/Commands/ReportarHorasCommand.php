<?php namespace Ghi\Operacion\Domain\Commands;

use Carbon\Carbon;

class ReportarHorasCommand {

    /**
     * @var
     */
    public $idEquipo;

    /**
     * @var
     */
    public $fecha;

    /**
     * @var
     */
    public $idTipoHora;

    /**
     * @var
     */
    public $cantidad;

    /**
     * @var
     */
    public $idConcepto;

    /**
     * @var
     */
    public $conCargo;

    /**
     * @var
     */
    public $observaciones;

    /**
     * @var
     */
    public $usuario;

    /**
     * @param $cantidad
     * @param $conCargo
     * @param $fecha
     * @param $idConcepto
     * @param $idEquipo
     * @param $idTipoHora
     * @param $observaciones
     * @param $usuario
     */
    function __construct($idEquipo, $fecha, $idTipoHora, $cantidad, $idConcepto, $conCargo, $observaciones, $usuario)
    {
        $this->cantidad = $cantidad;
        $this->conCargo = empty($conCargo) ? 0 : $conCargo;
        $this->fecha = new Carbon($fecha);
        $this->idConcepto = $idConcepto;
        $this->idEquipo = $idEquipo;
        $this->idTipoHora = $idTipoHora;
        $this->observaciones = $observaciones;
        $this->usuario = $usuario;
    }

}