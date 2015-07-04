<?php

namespace Ghi\Http\Controllers\ReportesActividad;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Core\Conceptos\ConceptoRepository;
use Ghi\Domain\ReportesActividad\Actividad;
use Ghi\Domain\ReportesActividad\Exceptions\ConceptoNoEsMedibleException;
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

        $this->reporteRepository = $reporteRepository;
        $this->conceptoRepository = $conceptoRepository;
        $this->almacenRepository = $almacenRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @return Response
     */
    public function create($idAlmacen, $idReporte)
    {
        $almacen = $this->almacenRepository->getById($idAlmacen);
        $tiposHora = $this->reporteRepository->getTiposHoraList();
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('actividades.create', compact('reporte', 'tiposHora', 'almacen'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @param ReportarActividadRequest $request
     * @return Response
     * @throws ConceptoNoEsMedibleException
     */
    public function store($idAlmacen, $idReporte, ReportarActividadRequest $request)
    {
        try {
            $reporte = $this->reporteRepository->getById($idReporte);

            $actividad = new Actividad($request->all());
            $actividad->id_tipo_hora = $request->get('tipo_hora');
            $actividad->creadoPor()->associate(auth()->user());

            if ($request->has('id_concepto')) {
                $concepto = $this->conceptoRepository->getById($request->get('id_concepto'));
                $actividad->destino()->associate($concepto);
            }

            $actividad->reportarEn($reporte);
        } catch (ReglaNegocioException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        flash()->success('La actividad fue agregada al reporte.');

        return redirect()->route('reportes.show', [$idAlmacen, $idReporte, '#actividades-reportadas']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @param $idActividad
     * @return Response
     */
    public function destroy($idAlmacen, $idReporte, $idActividad)
    {
        $reporte = $this->reporteRepository->getById($idReporte);

        if ($reporte->cerrado) {
            flash()->error('Este reporte no puede ser modificado');

            return redirect()->back();
        }

        $reporte->actividades()->find($idActividad)->delete();

        return redirect()->route('reportes.show', [$idAlmacen, $idReporte]);
    }
}
