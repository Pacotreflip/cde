<?php namespace Ghi\Operacion\Infraestructure;

use Ghi\Core\App\BaseRepository;
use Ghi\Conciliacion\Domain\Exceptions\NoExisteOperacionPorConciliarEnPeriodoException;
use Ghi\Operacion\Domain\ReporteActividadRepository;
use Ghi\Operacion\Domain\Hora;
use Ghi\Operacion\Domain\HoraTipo;
use Ghi\Operacion\Domain\ReporteActividad;
use Ghi\Operacion\Domain\TipoHora;
use Ghi\SharedKernel\Models\Equipo;

class EloquentReporteActividadRepository extends BaseRepository implements ReporteActividadRepository
{
    /**
     * Obtiene un reporte de operacion por su id
     *
     * @param $id
     * @return \Illuminate\Support\Collection|static
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
     * @return mixed
     */
    public function getByIdAlmacen($idAlmacen)
    {
        return ReporteActividad::whereIdObra($this->context->getTenantId())
            ->where('id_almacen', $idAlmacen)
            ->with('usuarioRegistro')
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtiene los reportes de horas de un almacen paginados
     *
     * @param $idAlmacen
     * @param int $howMany
     * @return mixed
     */
    public function getByIdAlmacenPaginated($idAlmacen, $howMany = 30)
    {
        return ReporteActividad::where('id_obra', $this->context->getId())
            ->where('id_almacen', $idAlmacen)
            ->orderBy('fecha', 'desc')
            ->paginate($howMany);
    }

    /**
     * Busca un reporte de operacion por fecha
     *
     * @param $idAlmacen
     * @param $fecha
     * @return mixed
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
     * @return mixed
     */
    public function getByPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return ReporteActividad::where('id_obra', $this->context->getId())
            ->where('id_almacen', $idAlmacen)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->get();
    }

    /**
     * Indica si existe un reporte de operacion en la fecha indicada
     *
     * @param $idAlmacen
     * @param $fecha
     * @return mixed
     */
    public function existeEnFecha($idAlmacen, $fecha)
    {
        return ReporteActividad::where('id_obra', $this->context->getId())
            ->where('id_almacen', $idAlmacen)
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * Indica si existen horas por conciliar de un almacen en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws NoExisteOperacionPorConciliarEnPeriodoException
     * @return boolean
     */
    public function existenHorasPorConciliarEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        $existe = ReporteActividad::where('id_almacen', $idAlmacen)
            ->where('conciliado', false)
            ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
            ->exists();

        if ( ! $existe)
        {
            throw new NoExisteOperacionPorConciliarEnPeriodoException;
        }

        return true;
    }

    /**
     * Persiste un reporte de operacion
     *
     * @param ReporteActividad $operacion
     * @return bool
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
     * @return mixed
     */
    public function delete(ReporteActividad $reporte)
    {
        $reporte->delete();
    }

    /**
     * Obtiene los tipos de hora en formato de lista (id, descripcion)
     */
    public function getTiposHoraList()
    {
        return TipoHora::all()
            ->lists('descripcion', 'id');
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
     * Obtiene la suma total de horas reportadas en un periodo por tipo de hora
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @param $tipoHora
     * @return mixed
     */
    public function sumaHorasPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal, $tipoHora)
    {
        return Hora::whereHas('reporte', function($query) use($idAlmacen, $fechaInicial, $fechaFinal)
            {
                $query->whereIdAlmacen($idAlmacen)
                    ->whereBetween('fecha', [$fechaInicial, $fechaFinal])
                    ->whereConciliado(false);
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
     */
    public function getHorasHorometroPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        $horometroIncial = $this->getHorometroIncialPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

        $horometroFinal = $this->getHorometroFinalPorPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

        if ($horometroFinal <= 0)
        {
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
     * @return mixed
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
     * @return mixed
     */
    public function getHorasEfectivas($id)
    {
        return Hora::where('id_reporte', $id)
            ->whereIn('id_tipo_hora', [HoraTipo::EFECTIVA, HoraTipo::OCIO, HoraTipo::REPARACION_MAYOR])
            ->get();
    }
}