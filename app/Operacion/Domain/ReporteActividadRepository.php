<?php namespace Ghi\Operacion\Domain;

use Ghi\Conciliacion\Domain\Exceptions\NoExisteOperacionPorConciliarEnPeriodoException;
use Ghi\SharedKernel\Models\Equipo;

interface ReporteActividadRepository
{
    /**
     * Obtiene un reporte de operacion por su id
     *
     * @param $id
     * @return \Illuminate\Support\Collection|static
     */
    public function getById($id);

    /**
     * Obtiene los reportes de horas de un almacen
     *
     * @param $idAlmacen
     * @return mixed
     */
    public function getByIdAlmacen($idAlmacen);

    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $idAlmacen
     * @param int $howMany
     * @return mixed
     */
    public function getByIdAlmacenPaginated($idAlmacen, $howMany = 30);

    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $idAlmacen
     * @param $fecha
     * @return mixed
     */
    public function getByFecha($idAlmacen, $fecha);

    /**
     * Obtiene los reportes de operacion de un equipo en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function getByPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Indica si existe un reporte de operacion en la fecha indicada
     *
     * @param $idAlmacen
     * @param $fecha
     * @return mixed
     */
    public function existeEnFecha($idAlmacen, $fecha);

    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws NoExisteOperacionPorConciliarEnPeriodoException
     * @return boolean
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
     * @return mixed
     */
    public function delete(ReporteActividad $reporte);

    /**
     * Obtiene los tipos de hora en formato de lista (id, descripcion)
     */
    public function getTiposHoraList();

    /**
     * Elimina un registro de horas del reporte de operacion
     *
*@param ReporteActividad $reporte
     * @param $idHora
     * @return
     */
    public function deleteHora(ReporteActividad $reporte, $idHora);

    /**
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $tipoHora
     * @return mixed
     */
    public function sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, $tipoHora);

    /**
     * Obtiene la suma total de horas efectivas reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sumaHorasEfectivasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas reparacion menor reportadas en un periodo de tiempo
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sumaHorasReparacionMenorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas reparacion mayor reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sumaHorasReparacionMayorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas mantenimiento reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sumaHorasMantenimientoPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene la suma total de horas ocio reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sumaHorasOcioPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene horometro inicial de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function getHorometroIncialPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el horometro final de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function getHorometroFinalPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el numero total de horas horometro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function getHorasHorometroPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el numero de dias que tienen operacion en un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function diasConOperacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene las horas efectivas de un reporte de operacion
     *
     * @param $id
     * @return mixed
     */
    public function getHorasEfectivas($id);

}