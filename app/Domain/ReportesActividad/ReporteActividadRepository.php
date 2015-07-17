<?php

namespace Ghi\Domain\ReportesActividad;

interface ReporteActividadRepository
{
    /**
     * Obtiene un reporte de actividades por su id
     *
     * @param $id
     * @return ReporteActividad
     */
    public function getById($id);

    /**
     * Obtiene los reportes de horas de un almacen
     *
     * @param $id_almacen
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacen($id_almacen);

    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $id_almacen
     * @param int $how_many
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacenPaginated($id_almacen, $how_many = 30);

    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $id_almacen
     * @param $fecha
     * @return ReporteActividad
     */
    public function getByFecha($id_almacen, $fecha);

    /**
     * Obtiene los reportes de operacion de un equipo en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Indica si un reporte de operacion existe en la fecha indicada
     *
     * @param $id_almacen
     * @param $fecha
     * @return bool
     */
    public function existeEnFecha($id_almacen, $fecha);

    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionAprobadaEnPeriodoException
     * @return bool
     */
    public function existenReportesPorConciliarEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Persiste un reporte de operacion
     *
     * @param ReporteActividad $reporte
     * @return ReporteActividad
     */
    public function save(ReporteActividad $reporte);

    /**
     * Elimina un reporte de actividades
     *
     * @param ReporteActividad $reporte
     * @throws ReporteOperacionAprobadoException
     */
    public function delete(ReporteActividad $reporte);

    /**
     * Elimina un registro de actividad de un reporte
     *
     * @param ReporteActividad $reporte
     * @param $id
     * @throws ReporteOperacionAprobadoException
     */
    public function deleteHora(ReporteActividad $reporte, $id);

    /**
     * Obtiene los tipos de hora en formato de lista (id, descripcion)
     *
     * @return array
     */
    public function getTiposHoraList();

    /**
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @param $tipo_hora
     * @return float
     */
    public function sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, $tipo_hora);

    /**
     * Obtiene la suma total de horas efectivas reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasEfectivasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene la suma total de horas reparacion menor reportadas en un periodo de tiempo
     *
*@param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasReparacionMenorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene la suma total de horas reparacion mayor reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @param bool $con_cargo
     * @return float
     */
    public function sumaHorasReparacionMayorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, $con_cargo = false);

    /**
     * Obtiene la suma total de horas mantenimiento reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasMantenimientoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene la suma total de horas ocio reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasOcioPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene la suma total de horas traslado reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasTrasladoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene horometro inicial de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene el horometro final de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene el numero total de horas horometro de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorasHorometroPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene el numero de dias que tienen operacion en un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function diasConOperacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Crea un nuevo reporte y lo almacena
     *
     * @param ReporteActividad $reporte
     * @return ReporteActividad
     * @throws ReporteDeOperacionYaExisteException
     */
    public function store(ReporteActividad $reporte);
}
