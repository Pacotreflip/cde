<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\ReportesActividad\Exceptions\ReporteDeOperacionYaExisteException;
use Ghi\Domain\ReportesActividad\Exceptions\ReporteOperacionAprobadoException;

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
     * @param $id_almacen
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacen($id_almacen)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $id_almacen
     * @param int $how_many
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByIdAlmacenPaginated($id_almacen, $how_many = 30)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->orderBy('fecha', 'desc')
            ->paginate($how_many);
    }

    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $id_almacen
     * @param $fecha
     * @return ReporteActividad
     */
    public function getByFecha($id_almacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->where('fecha', $fecha)
            ->firstOrFail();
    }

    /**
     * Obtiene los reportes de operacion de un equipo en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return \Illuminate\Database\Eloquent\Collection|ReporteActividad
     */
    public function getByPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->get();
    }

    /**
     * Indica si un reporte de operacion existe en la fecha indicada
     *
     * @param $id_almacen
     * @param $fecha
     * @return bool
     */
    public function existeEnFecha($id_almacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionAprobadaEnPeriodoException
     * @return bool
     */
    public function existenReportesPorConciliarEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
//            ->where('aprobado', false)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->exists();
    }

    /**
     * Persiste un reporte de operacion
     *
     * @param ReporteActividad $reporte
     * @return ReporteActividad
     */
    public function save(ReporteActividad $reporte)
    {
        $reporte->save();

        return $reporte;
    }

    /**
     * Elimina un reporte de actividades
     *
     * @param ReporteActividad $reporte
     * @throws ReporteOperacionAprobadoException
     * @throws \Exception
     */
    public function delete(ReporteActividad $reporte)
    {
        if ($reporte->aprobado) {
            throw new ReporteOperacionAprobadoException;
        }

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
        $hora = $reporte->horas()->where('id', '=', $idHora)->firstOrfail();

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
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @param $tipo_hora
     * @return float
     */
    public function sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, $tipo_hora)
    {
        return Actividad::whereHas('reporte', function ($query) use ($id_almacen, $fecha_inicial, $fecha_final) {
                $query->where('id_almacen', $id_almacen)
                    ->whereBetween('fecha', [$fecha_inicial, $fecha_final]);
//                    ->where('aprobado', false);
            })
            ->where('id_tipo_hora', $tipo_hora)
            ->sum('cantidad');
    }

    /**
     * Obtiene la suma total de horas efectivas reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasEfectivasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::EFECTIVA);
    }

    /**
     * Obtiene la suma total de horas reparacion menor reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasReparacionMenorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::REPARACION_MENOR);
    }

    /**
     * Obtiene la suma total de horas reparacion mayor reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasReparacionMayorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::REPARACION_MAYOR);
    }

    /**
     * Obtiene la suma total de horas mantenimiento reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasMantenimientoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::MANTENIMIENTO);
    }

    /**
     * Obtiene la suma total de horas ocio reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasOcioPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::OCIO);
    }

    /**
     * Obtiene la suma total de horas traslado reportadas en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function sumaHorasTrasladoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, HoraTipo::TRASLADO);
    }

    /**
     * Obtiene horometro inicial de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->min('horometro_inicial');
    }

    /**
     * Obtiene el horometro final de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->max('horometro_final');
    }

    /**
     * Obtiene el numero total de horas horometro de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function getHorasHorometroPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        $horometroIncial = $this->getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horometroFinal = $this->getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

        if ($horometroFinal <= 0) {
            return 0;
        }

        return $horometroFinal - $horometroIncial;
    }

    /**
     * Obtiene el numero de dias que tienen operacion en un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    public function diasConOperacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->count();
    }

    /**
     * Persiste los cambios de un reporte en el almacenamiento
     *
     * @param ReporteActividad $reporte
     * @return ReporteActividad
     * @throws ReporteOperacionAprobadoException
     */
    public function update(ReporteActividad $reporte)
    {
        if ($reporte->aprobado) {
            throw new ReporteOperacionAprobadoException;
        }

        $reporte->save();

        return $reporte;
    }

    /**
     * Crea un nuevo reporte y lo almacena
     *
     * @param ReporteActividad $reporte
     * @return ReporteActividad
     * @throws ReporteDeOperacionYaExisteException
     */
    public function store(ReporteActividad $reporte)
    {
        if ($this->existeEnFecha($reporte->id_almacen, $reporte->fecha)) {
            throw new ReporteDeOperacionYaExisteException;
        }

        $reporte->save();

        return $reporte;
    }
}
