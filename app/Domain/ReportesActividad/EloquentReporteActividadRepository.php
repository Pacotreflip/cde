<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\Almacenes\HoraMensual;
use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\SharedKernel\Models\Equipo;

class EloquentReporteActividadRepository extends BaseRepository implements ReporteActividadRepository
{
    /**
     * Obtiene un reporte de operacion por su id
     *
     * @param $id
     * @return ReporteActividad
     */
    public function getById($id)
    {
        return ReporteActividad::where('id', $id)
            ->firstOrFail();
    }

    /**
     * Obtiene los reportes de horas de un almacen
     *
     * @param $idAlmacen
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacen($idAlmacen)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $idAlmacen
     * @param int $howMany
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacenPaginated($idAlmacen, $howMany = 30)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->orderBy('fecha', 'desc')
            ->paginate($howMany);
    }

    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $idAlmacen
     * @param $fecha
     * @return ReporteActividad
     */
    public function getByFecha($idAlmacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->where('fecha', $fecha)
            ->firstOrFail();
    }

    /**
     * Obtiene los reportes de operacion de un equipo en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->get();
    }

    /**
     * Indica si existe un reporte de operacion en la fecha indicada
     *
     * @param $idAlmacen
     * @param $fecha
     * @return bool
     */
    public function existeEnFecha($idAlmacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionPorConciliarEnPeriodoException
     * @return bool
     */
    public function existenHorasPorConciliarEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->where('conciliado', false)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->exists();
    }

    /**
     * Persiste un reporte de operacion
     *
     * @param ReporteActividad $operacion
     * @return ReporteActividad
     */
    public function save(ReporteActividad $operacion)
    {
        $operacion->save();

        return $operacion;
    }

    /**
     * Elimina un reporte de operacion
     *
     * @param ReporteActividad $reporte
     */
    public function delete(ReporteActividad $reporte)
    {
        $reporte->delete();
    }

    /**
     * Elimina un registro de horas del reporte de operacion
     *
     * @param ReporteActividad $reporte
     * @param $idHora
     */
    public function deleteHora(ReporteActividad $reporte, $idHora)
    {
        $hora = $reporte->horas()
            ->where('id', '=', $idHora)
            ->firstOrfail();

        $hora->delete();
    }

    /**
     * Obtiene los tipos de hora en formato de lista (id, descripcion)
     *
     * @return array
     */
    public function getTiposHoraList()
    {
        return TipoHora::lists('descripcion', 'id')->all();
    }

    /**
     * Obtiene las horas de contrato vigentes en un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorasContratoEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return HoraMensual::where('id_almacen', $idAlmacen)
            ->whereBetween('inicio_vigencia', [$fechaInicial, $fechaFinal])
            ->orderBy('inicio_vigencia', 'DESC')
            ->firstOrFail()
            ->horas_contrato;
    }

    /**
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $tipoHora
     * @return float
     */
    public function sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, $tipoHora)
    {
        return Actividad::whereHas('reporte', function ($query) use ($idAlmacen, $fechaInicial, $fechaFinal) {
                $query->where('id_almacen', $idAlmacen)
                    ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
                    ->where('conciliado', false);
            })
            ->where('id_tipo_hora', $tipoHora)
            ->sum('cantidad');
    }

    /**
     * Obtiene la suma total de horas efectivas reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasEfectivasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return $this->sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, HoraTipo::EFECTIVA);
    }

    /**
     * Obtiene la suma total de horas reparacion menor reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasReparacionMenorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return $this->sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, HoraTipo::REPARACION_MENOR);
    }

    /**
     * Obtiene la suma total de horas reparacion mayor reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasReparacionMayorPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return $this->sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, HoraTipo::REPARACION_MAYOR);
    }

    /**
     * Obtiene la suma total de horas mantenimiento reportadas en un periodo de tiempo
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasMantenimientoPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return $this->sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, HoraTipo::MANTENIMIENTO);
    }

    /**
     * Obtiene la suma total de horas ocio reportadas en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function sumaHorasOcioPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return $this->sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, HoraTipo::OCIO);
    }

    /**
     * Obtiene horometro inicial de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorometroIncialPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->min('horometro_inicial');
    }

    /**
     * Obtiene el horometro final de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorometroFinalPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->max('horometro_final');
    }

    /**
     * Obtiene el numero total de horas horometro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function getHorasHorometroPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        $horometroIncial = $this->getHorometroIncialPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

        $horometroFinal = $this->getHorometroFinalPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

        if ($horometroFinal <= 0) {
            return 0;
        }

        return $horometroFinal - $horometroIncial;
    }

    /**
     * Obtiene el numero de dias que tienen operacion en un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return float
     */
    public function diasConOperacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_almacen', $idAlmacen)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->count();
    }

    /**
     * Obtiene las horas efectivas de un reporte de operacion
     *
     * @param $id
     * @return float
     */
    public function getHorasEfectivas($id)
    {
        return Actividad::where('id_reporte', $id)
            ->whereIn('id_tipo_hora', [HoraTipo::EFECTIVA, HoraTipo::OCIO, HoraTipo::REPARACION_MAYOR])
            ->get();
    }

    /**
     * Borra un reporte de actividades
     *
     * @param $id
     * @throws \Ghi\Domain\Core\Exceptions\ReglaNegocioException
     */
    public function borraReporte($id)
    {
        $reporte = $this->getById($id);

        if ($reporte->cerrado) {
            throw new ReglaNegocioException('Este reporte no puede ser modificado por que ya esta cerrado.');
        }

        $reporte->delete();
    }
}
