<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\SharedKernel\Models\Equipo;

interface ReporteActividadRepository
{
    /**
     * Obtiene un reporte de operacion por su id
     *
     * @param $id
     * @return ReporteActividad
     */
    public function getById($id);


    /**
     * Obtiene los reportes de horas de un almacen
     *
     * @param $idAlmacen
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacen($idAlmacen);


    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $idAlmacen
     * @param int $howMany
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacenPaginated($idAlmacen, $howMany = 30);


    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $idAlmacen
     * @param $fecha
     * @return ReporteActividad
     */
    public function getByFecha($idAlmacen, $fecha);


    /**
     * Obtiene los reportes de operacion de un equipo en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByPeriodo($idAlmacen, $fechaInicial, $fechaFinal);


    /**
     * Indica si existe un reporte de operacion en la fecha indicada
     *
     * @param $idAlmacen
     * @param $fecha
     * @return bool
     */
    public function existeEnFecha($idAlmacen, $fecha);


    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionPorConciliarEnPeriodoException
     * @return bool
     */
    public function existenHorasPorConciliarEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);


    /**
     * Persiste un reporte de operacion
     *
     * @param ReporteActividad $operacion
     * @return bool
     */
    public function save(ReporteActividad $operacion);


    /**
     * Elimina un reporte de operacion
     *
     * @param ReporteActividad $reporte
     */
    public function delete(ReporteActividad $reporte);


    /**
     * Elimina un registro de horas del reporte de operacion
     *
     * @param ReporteActividad $reporte
     * @param $idHora
     */
    public function deleteHora(ReporteActividad $reporte, $idHora);


    /**
     * Obtiene los tipos de hora en formato de lista (id, descripcion)
     *
     * @return array
     */
    public function getTiposHoraList();


    /**
     * Obtiene las horas de contrato vigentes en un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorasContratoEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);


    /**
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $tipoHora
     * @return float
     */
    public function sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, $tipoHora);


    /**
     * Obtiene la suma total de horas efectivas reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasEfectivasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas reparacion menor reportadas en un periodo de tiempo
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasReparacionMenorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas reparacion mayor reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasReparacionMayorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas mantenimiento reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasMantenimientoPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas ocio reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return dloat
     */
    public function sumaHorasOcioPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene horometro inicial de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorometroIncialPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el horometro final de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorometroFinalPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el numero total de horas horometro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorasHorometroPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el numero de dias que tienen operacion en un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function diasConOperacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene las horas efectivas de un reporte de operacion
     *
     * @param $id
     * @return float
     */
    public function getHorasEfectivas($id);

    /**
     * Borra un reporte de actividades
     *
     * @param $id
     * @throws \Ghi\Domain\Core\Exceptions\ReglaNegocioException
     */
    public function borraReporte($id);
}
