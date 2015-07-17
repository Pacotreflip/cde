<?php

namespace Ghi\Http\Controllers\ReportesActividad;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Core\Conceptos\ConceptoRepository;
use Ghi\Domain\ReportesActividad\Actividad;
use Ghi\Domain\ReportesActividad\Exceptions\ConceptoNoEsMedibleException;
use Ghi\Domain\ReportesActividad\TipoHora;
use Ghi\Domain\ReportesActividad\ReporteActividadRepository;
use Ghi\Http\Requests\ReportesActividad\ReportarActividadRequest;
use Ghi\Domain\ReportesActividad\Commands\ReportarHorasCommand;
use Ghi\Http\Controllers\Controller;

class ActividadesController extends Controller
{
    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @var ConceptoRepository
     */
    private $conceptoRepository;

    /**
     * @var AlmacenMaquinariaRepository
     */
    private $almacenRepository;

    /**
     * @param ReporteActividadRepository $reporteRepository
     * @param ConceptoRepository $conceptoRepository
     * @param AlmacenMaquinariaRepository $almacenRepository
     */
    public function __construct(
        ReporteActividadRepository $reporteRepository,
        ConceptoRepository $conceptoRepository,
        AlmacenMaquinariaRepository $almacenRepository
    ) {
        $this->middleware('auth');
        $this->middleware('context');

        $this->reporteRepository  = $reporteRepository;
        $this->conceptoRepository = $conceptoRepository;
        $this->almacenRepository  = $almacenRepository;
    }


    /**
     * Muestra un formulario para crear un registro de actividades.
     *
     * @param $id_almacen
     * @param $id
     * @return Response
     */
    public function create($id_almacen, $id)
    {
        $almacen    = $this->almacenRepository->getById($id_almacen);
        $tipos_hora = $this->reporteRepository->getTiposHoraList();
        $reporte    = $this->reporteRepository->getById($id);

        return view('actividades.create', compact('reporte', 'tipos_hora', 'almacen'));
    }


    /**
     * Almacena un registro de actividades.
     *
     * @param ReportarActividadRequest $request
     * @param $id_almacen
     * @param $id_reporte
     * @return Response
     * @throws ConceptoNoEsMedibleException
     */
    public function store(ReportarActividadRequest $request, $id_almacen, $id_reporte)
    {
        $reporte = $this->reporteRepository->getById($id_reporte);
        $except = [];

        if (! $request->has('hora_inicial')) {
            $except[] = 'hora_inicial';
        }

        if (! $request->has('hora_final')) {
            $except[] = 'hora_final';
        }

        $actividad = new Actividad($request->except($except));
        $actividad->tipo_hora = $request->get('tipo_hora');

        if ($request->has('id_concepto')) {
            $concepto = $this->conceptoRepository->getById($request->get('id_concepto'));
            $actividad->destino()->associate($concepto);
        }

        // Cuando son horas de reparacion mayor por default se marcan como sin cargo a la empresa
        if ($request->get('tipo_hora') === TipoHora::REPARACION_MAYOR && ! $request->has('con_cargo_empresa')) {
            $actividad->con_cargo_empresa = false;
        }

        $actividad->creadoPor()->associate(auth()->user());
        $actividad->reportarEn($reporte);

        flash()->success('La actividad fue agregada al reporte.');

        return redirect()->route('reportes.show', [$id_almacen, $id_reporte, '#actividades-reportadas']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id_almacen
     * @param $id_reporte
     * @param $id
     * @return Response
     */
    public function destroy($id_almacen, $id_reporte, $id)
    {
        $reporte = $this->reporteRepository->getById($id_reporte);
        $this->reporteRepository->deleteHora($reporte, $id);

        return redirect()->route('reportes.show', [$id_almacen, $id_reporte]);
    }
}
