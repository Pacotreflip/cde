<?php namespace Ghi\Conciliacion\Infraestructure;

use Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository;
use Ghi\Conciliacion\Domain\Rentas\EntradaEquipo;
use Ghi\Conciliacion\Domain\Exceptions\EquipoSinContratoVigenteEnPeriodo;
use Ghi\Conciliacion\Domain\Rentas\ContratoRenta;
use Ghi\Core\App\BaseRepository;

class EloquentContratoRentaRepository extends BaseRepository implements ContratoRentaRepository
{

    /**
     * Obtiene los ids de las ordenes de renta (contratos) de un equipo
     *
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @return mixed
     */
    public function getContratosPorEquipo($idObra, $idProveedor, $idAlmacen)
    {
        $ordenesIds = EntradaEquipo::whereIdObra($idObra)
            ->whereIdEmpresa($idProveedor)
            ->whereHas('items', function($query) use($idAlmacen)
            {
                $query->whereIdAlmacen($idAlmacen);
            })->lists('id_antecedente');

        return ContratoRenta::whereIn('id_transaccion', $ordenesIds)->get();
    }

    /**
     * Obtiene el id de la ultima orden de renta (contrato) vigente de un periodo
     *
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    protected function getIdContratoRentaVigenteEnPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal)
    {
        return EntradaEquipo::selectRaw('top 1 id_antecedente, cumplimiento')
            ->whereIdObra($idObra)
            ->where('cumplimiento', '<=', $fechaFinal)
            ->whereIdEmpresa($idProveedor)
            ->whereHas('items', function($query) use($idAlmacen)
            {
                $query->whereIdAlmacen($idAlmacen);
            })
            ->orderBy('cumplimiento', 'desc')
            ->lists('id_antecedente');
    }

    /**
     * Obtiene el contrato vigente de un equipo en un periodo
     *
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     * @throws EquipoSinContratoVigenteEnPeriodo
     */
    public function getContratoVigenteDeEquipoPorPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal)
    {
        $ordenesIds = $this->getIdContratoRentaVigenteEnPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal);

        if (count($ordenesIds) <= 0)
        {
            throw new EquipoSinContratoVigenteEnPeriodo;
        }

        $contrato = ContratoRenta::whereIn('id_transaccion', $ordenesIds)->first();

        return $contrato;
    }

    /**
     * Obtiene el numero de horas del contrato vigente de un equipo
     *
     * en un periodo
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     * @throws EquipoSinContratoVigenteEnPeriodo
     */
    public function getHorasContratoVigenteDeEquipoPorPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal)
    {
        $contrato = $this->getContratoVigenteDeEquipoPorPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal);

        return $contrato->cantidad;
    }
}