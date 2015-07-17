<?php

namespace Ghi\Domain\ReportesActividad;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\ReportesActividad\Exceptions\ReporteYaExisteException;
use Ghi\Domain\ReportesActividad\Exceptions\ReporteAprobadoException;

class EloquentReporteActividadRepository extends BaseRepository implements ReporteActividadRepository
{
    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        return ReporteActividad::where('id', $id)
            ->firstOrFail();
    }

    /**
     * {@inheritdoc}
     */
    public function getByIdAlmacen($id_almacen)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getByIdAlmacenPaginated($id_almacen, $how_many = 30)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->orderBy('fecha', 'desc')
            ->paginate($how_many);
    }

    /**
     * {@inheritdoc}
     */
    public function getByFecha($id_almacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->where('fecha', $fecha)
            ->firstOrFail();
    }

    /**
     * {@inheritdoc}
     */
    public function getByPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function existeEnFecha($id_almacen, $fecha)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->where('fecha', $fecha)
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function existenReportesPorConciliarEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->where('aprobado', true)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function save(ReporteActividad $reporte)
    {
        $reporte->save();

        return $reporte;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ReporteActividad $reporte)
    {
        if ($reporte->aprobado) {
            throw new ReporteAprobadoException;
        }

        $reporte->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteHora(ReporteActividad $reporte, $id)
    {
        if ($reporte->aprobado) {
            throw new ReporteAprobadoException;
        }

        $reporte->actividades()->findOrFail($id)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function getTiposHoraList()
    {
        return [
            TipoHora::EFECTIVA         => new TipoHora(TipoHora::EFECTIVA),
            TipoHora::REPARACION_MAYOR => new TipoHora(TipoHora::REPARACION_MAYOR),
            TipoHora::REPARACION_MENOR => new TipoHora(TipoHora::REPARACION_MENOR),
            TipoHora::MANTENIMIENTO    => new TipoHora(TipoHora::MANTENIMIENTO),
            TipoHora::OCIO             => new TipoHora(TipoHora::OCIO),
            TipoHora::TRASLADO         => new TipoHora(TipoHora::TRASLADO),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, $tipo_hora)
    {
        return Actividad::whereHas('reporte', function ($query) use ($id_almacen, $fecha_inicial, $fecha_final) {
                $query->where('id_almacen', $id_almacen)
                    ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                    ->where('aprobado', true);
            })
            ->where('tipo_hora', $tipo_hora)
            ->sum('cantidad');
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasEfectivasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, TipoHora::EFECTIVA);
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasReparacionMenorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, TipoHora::REPARACION_MENOR);
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasReparacionMayorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, $con_cargo = false)
    {
        return Actividad::whereHas('reporte', function ($query) use ($id_almacen, $fecha_inicial, $fecha_final) {
            $query->where('id_almacen', $id_almacen)
                ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                    ->where('aprobado', true);
        })
            ->where('con_cargo_empresa', $con_cargo)
            ->where('tipo_hora', TipoHora::REPARACION_MAYOR)
            ->sum('cantidad');
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasMantenimientoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, TipoHora::MANTENIMIENTO);
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasOcioPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, TipoHora::OCIO);
    }

    /**
     * {@inheritdoc}
     */
    public function sumaHorasTrasladoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return $this->sumaHorasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final, TipoHora::TRASLADO);
    }

    /**
     * {@inheritdoc}
     */
    public function getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->min('horometro_inicial');
    }

    /**
     * {@inheritdoc}
     */
    public function getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->max('horometro_final');
    }

    /**
     * {@inheritdoc}
     */
    public function getHorasHorometroPorPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        $horometro_inicial = $this->getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horometro_final = $this->getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

        if ($horometro_final <= 0) {
            return 0;
        }

        return $horometro_final - $horometro_inicial;
    }

    /**
     * {@inheritdoc}
     */
    public function diasConOperacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ReporteActividad::where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->count();
    }

    /**
     * {@inheritdoc}
     */
    public function store(ReporteActividad $reporte)
    {
        if ($this->existeEnFecha($reporte->id_almacen, $reporte->fecha)) {
            throw new ReporteYaExisteException;
        }

        $reporte->save();

        return $reporte;
    }
}
