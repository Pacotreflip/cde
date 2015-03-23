<?php namespace Ghi\Operacion\Http\Controllers;

use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Almacenes\Domain\AlmacenMaquinariaRepository;
use Ghi\Core\Domain\Obras\ObraRepository;
use Ghi\Core\App\Facades\Context;
use Ghi\Operacion\Domain\ReporteActividad;
use Ghi\Operacion\Http\Requests\InicioActividadesRequest;
use Ghi\Operacion\Domain\ReporteActividadRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReportesActividadController extends Controller {

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
    function __construct(AlmacenMaquinariaRepository $almacenRepository, ReporteActividadRepository $reporteRepository)
    {
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
        try
        {
            if ($this->reporteRepository->existeEnFecha($idAlmacen, $request->get('fecha')))
            {
                throw new ReglaNegocioException('El reporte de actividades para la fecha indicada ya existe');
            }

            $almacen = $this->almacenRepository->getById($idAlmacen);

            $except = [];

            if ( ! $request->has('horometro_inicial'))
            {
                $except[] = 'horometro_inicial';
            }

            if ( ! $request->has('kilometraje_inicial'))
            {
                $except[] = 'kilometraje_inicial';
            }

            if ( ! $request->has('operador'))
            {
                $except[] = 'operador';
            }

            $reporte = new ReporteActividad($request->except($except));

            $reporte->almacen()->associate($almacen);
            $reporte->creadoPor()->associate(\Auth::user());

            $reporte->save();
        }
        catch(ReglaNegocioException $e)
        {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        Flash::success('El reporte de actividades fue generado. Ahora puede reportar las actividades');

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
        $reporte = $this->reporteRepository->getById($idReporte);

		return view('reportes.show')->withReporte($reporte);
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
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('reportes.edit')->withReporte($reporte);
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
        try
        {
            $reporte = $this->reporteRepository->getById($idReporte);

            if ($reporte->cerrado) {
                throw new ReglaNegocioException('Este reporte no puede ser modificado por que ya esta cerrado.');
            }

            if ($request->has('cerrar')) {
                $reporte->horometro_final = $request->get('horometro_final');
                $reporte->kilometraje_final = $request->get('kilometraje_final');
                $reporte->operador = $request->get('operador');
                $reporte->observaciones = $request->get('observaciones');
                $reporte->cerrar();
            } else {
                $reporte->update($request->all());
            }
        }
        catch(ReglaNegocioException $e)
        {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        Flash::success('Los cambios fueron guardados.');

        return redirect()->route('reportes.show', [$idAlmacen, $idReporte]);
    }


    /**
     * @param $idAlmacen
     * @param $idReporte
     * @return mixed
     */
    public function cierre($idAlmacen, $idReporte)
    {
        $reporte = $this->reporteRepository->getById($idReporte);

        return view('reportes.cierre')->withReporte($reporte);
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
        try
        {
            $this->reporteRepository->borraReporte($idReporte);
        }
        catch(ReglaNegocioException $e)
        {
            Flash::error($e->getMessage());

            return redirect()->back();
        }

        Flash::message('El reporte fue eliminado.');

        return redirect()->route('reportes.index', [$idAlmacen]);
	}

}
