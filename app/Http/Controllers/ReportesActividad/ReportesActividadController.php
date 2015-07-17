<?php

namespace Ghi\Http\Controllers\ReportesActividad;

use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\ReportesActividad\ReporteActividad;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\ReportesActividad\InicioActividadesRequest;
use Ghi\Domain\ReportesActividad\ReporteActividadRepository;
use Illuminate\Http\Request;

class ReportesActividadController extends Controller
{
    /**
     * @var AlmacenMaquinariaRepository
     */
    private $almacenRepository;

    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @param AlmacenMaquinariaRepository $almacenRepository
     * @param ReporteActividadRepository $reporteRepository
     */
    public function __construct(
        AlmacenMaquinariaRepository $almacenRepository,
        ReporteActividadRepository $reporteRepository
    ) {
        $this->middleware('auth');
        $this->middleware('context');

        $this->almacenRepository = $almacenRepository;
        $this->reporteRepository = $reporteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id_almacen
     * @return Response
     */
    public function index($id_almacen)
    {
        $reportes = $this->reporteRepository->getByIdAlmacenPaginated($id_almacen);

        $almacen = $this->almacenRepository->getById($id_almacen);

        return view('reportes.index', compact('almacen', 'reportes'));
    }

    /**
     * Muestra un formulario para crear un nuevo reporte de actividades
     *
     * @param $id_almacen
     * @return Response
     */
    public function create($id_almacen)
    {
        $almacen = $this->almacenRepository->getById($id_almacen);

        return view('reportes.create', compact('almacen'));
    }

    /**
     * Almacena un nuevo reporte de actividades
     *
     * @param $id_almacen
     * @param InicioActividadesRequest $request
     * @return Response
     * @throws ReglaNegocioException
     */
    public function store($id_almacen, InicioActividadesRequest $request)
    {
        $almacen = $this->almacenRepository->getById($id_almacen);
        $except = [];

        if (! $request->has('horometro_inicial')) {
            $except[] = 'horometro_inicial';
        }

        if (! $request->has('kilometraje_inicial')) {
            $except[] = 'kilometraje_inicial';
        }

        if (! $request->has('operador')) {
            $except[] = 'operador';
        }

        $reporte = new ReporteActividad($request->except($except));
        $reporte->almacen()->associate($almacen);
        $reporte->creadoPor()->associate(auth()->user());
        $this->reporteRepository->store($reporte);

        flash()->success('El reporte de actividades fue generado. Ahora puede reportar las actividades');

        return redirect()->route('reportes.show', [$id_almacen, $reporte]);
    }

    /**
     * Muestra un reporte de actividades.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @return Response
     */
    public function show($idAlmacen, $idReporte)
    {
        $almacen = $this->almacenRepository->getById($idAlmacen);
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('reportes.show')
            ->withAlmacen($almacen)
            ->withReporte($reporte);
    }

    /**
     * Muestra un formulario para modificar el reporte de actividades.
     *
     * @param $id_almacen
     * @param $id
     * @return Response
     */
    public function edit($id_almacen, $id)
    {
        $almacen = $this->almacenRepository->getById($id_almacen);
        $reporte = $this->reporteRepository->getById($id);

        return view('reportes.edit')
            ->withAlmacen($almacen)
            ->withReporte($reporte);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id_almacen
     * @param $id
     * @param Request $request
     * @throws \ReglaNegocioException
     * @return Response
     */
    public function update($id_almacen, $id, Request $request)
    {
        $reporte = $this->reporteRepository->getById($id);

        if ($request->has('aprobar')) {
            $reporte->horometro_final   = $request->get('horometro_final');
            $reporte->kilometraje_final = $request->get('kilometraje_final');
            $reporte->operador          = $request->get('operador');
            $reporte->observaciones     = $request->get('observaciones');
            $reporte->aprobar();
        }

        $reporte->fill($request->all());
        $this->reporteRepository->save($reporte);

        flash()->success('Los cambios fueron guardados.');

        return redirect()->route('reportes.show', [$id_almacen, $id]);
    }

    /**
     * @param $id_almacen
     * @param $id
     * @return mixed
     */
    public function aprobar($id_almacen, $id)
    {
        $almacen = $this->almacenRepository->getById($id_almacen);
        $reporte = $this->reporteRepository->getById($id);

        return view('reportes.aprobar')
            ->withAlmacen($almacen)
            ->withReporte($reporte);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id_almacen
     * @param $id
     * @return Response
     */
    public function destroy($id_almacen, $id)
    {
        $reporte = $this->reporteRepository->getById($id);

        $this->reporteRepository->delete($reporte);

        flash('El reporte fue eliminado.');

        return redirect()->route('reportes.index', [$id_almacen]);
    }
}
