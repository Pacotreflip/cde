<?php namespace Ghi\Core\Domain\Obras;

class RegistrarObraCommand {

    /**
     * @var string
     */
    public $nombre;

    /**
     * @var string
     */
    public $descripcion;

    /**
     * @var string
     */
    public $estadoObra;

    /**
     * @var string
     */
    public $constructora;

    /**
     * @var string
     */
    public $cliente;

    /**
     * @var string
     */
    public $facturar;

    /**
     * @var string
     */
    public $responsable;

    /**
     * @var string
     */
    public $rfc;

    /**
     * @var string
     */
    public $direccion;

    /**
     * @var string
     */
    public $ciudad;

    /**
     * @var string
     */
    public $codigoPostal;

    /**
     * @var string
     */
    public $estado;

    /**
     * @var string
     */
    public $moneda;

    /**
     * @var string
     */
    public $iva;

    /**
     * @var string
     */
    public $fechaInicial;

    /**
     * @var string
     */
    public $fechaFinal;

    /**
     * @var string
     */
    public $connection;


    /**
     * @param $nombre
     * @param $descripcion
     * @param $estadoObra
     * @param $constructora
     * @param $cliente
     * @param $facturar
     * @param $responsable
     * @param $rfc
     * @param $direccion
     * @param $ciudad
     * @param $codigoPostal
     * @param $estado
     * @param $moneda
     * @param $iva
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $connection
     */
    public function __construct(
        $nombre, $descripcion, $estadoObra, $constructora, $cliente, $facturar,
        $responsable, $rfc, $direccion, $ciudad, $codigoPostal, $estado, $moneda,
        $iva, $fechaInicial, $fechaFinal, $connection
    )
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estadoObra = $estadoObra;
        $this->constructora = $constructora;
        $this->cliente = $cliente;
        $this->facturar = $facturar;
        $this->responsable = $responsable;
        $this->rfc = $rfc;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->codigoPostal = $codigoPostal;
        $this->estado = $estado;
        $this->moneda = $moneda;
        $this->iva = $iva;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
        $this->connection = $connection;
    }

}