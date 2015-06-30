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
     * @param $idAlmacen
     * @return Response
     */
    public function index($idAlmacen)
    {
        $reportes = $this->reporteRepository->getByIdAlmacenPaginated($idAlmacen);

        $almacen = $this->almacenRepository->getById($idAlmacen);

        return view('reportes.index', compact('almacen', 'reportes'));
    }

    /**
     * Muestra un formulario para crear un nuevo reporte de actividades
     *
     * @param $idAlmacen
     * @return Response
     */
    public function create($idAlmacen)
    {
        $almacen = $this->almacenRepository->getById($idAlmacen);

        return view('reportes.create', compact('almacen'));
    }


    /**
     * Almacena un nuevo reporte de actividades
     *
     * @param $idAlmacen
     * @param InicioActividadesRequest $request
     * @return Response
     * @throws ReglaNegocioException
     */
    public function store($idAlmacen, InicioActividadesRequest $request)
    {
        try {
            if ($this->reporteRepository->existeEnFecha($idAlmacen, $request->get('fecha'))) {
                throw new ReglaNegocioException('El reporte de actividades para la fecha indicada ya existe');
            }

            $almacen = $this->almacenRepository->getById($idAlmacen);

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

            $reporte->save();
        } catch (ReglaNegocioException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        flash()->success('El reporte de actividades fue generado. Ahora puede reportar las actividades');

        return redirect()->route('reportes.show', [$idAlmacen, $reporte->id]);
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
     * @param $idAlmacen
     * @param $idReporte
     * @return Response
     */
    public function edit($idAlmacen, $idReporte)
    {
        $almacen = $this->almacenRepository->getById($idAlmacen);
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('reportes.edit')
            ->withAlmacen($almacen)
            ->withReporte($reporte);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @param Request $request
     * @throws \ReglaNegocioException
     * @return Response
     */
    public function update($idAlmacen, $idReporte, Request $request)
    {
        $reporte = $this->reporteRepository->getById($idReporte);

        try {
            if ($reporte->cerrado) {
                throw new ReglaNegocioException('Este reporte no puede ser modificado por que ya esta cerrado.');
            }

            if ($request->has('cerrar')) {
                $reporte->horometro_final = $request->get('horometro_final');
                $reporte->kilometraje_final = $request->get('kilometraje_final');
                $reporte->operador = $request->get('operador');
                $reporte->observaciones = $request->get('observaciones');
                $reporte->cerrar();
            }

            $reporte->update($request->all());
        } catch (ReglaNegocioException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        flash()->success('Los cambios fueron guardados.');

        return redirect()->route('reportes.show', [$idAlmacen, $idReporte]);
    }


    /**
     * @param $idAlmacen
     * @param $idReporte
     * @return mixed
     */
    public function cierre($idAlmacen, $idReporte)
    {
        $almacen = $this->almacenRepository->getById($idAlmacen);
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('reportes.cierre')
            ->withAlmacen($almacen)
            ->withReporte($reporte);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @return Response
     */
    public function destroy($idAlmacen, $idReporte)
    {
        try {
            $this->reporteRepository->borraReporte($idReporte);
        } catch (ReglaNegocioException $e) {
            flash()->error($e->getMessage());

            return redirect()->back();
        }

        flash('El reporte fue eliminado.');

        return redirect()->route('reportes.index', [$idAlmacen]);
    }
}
