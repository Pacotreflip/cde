<?php namespace Ghi\Operacion\Http\Controllers;

use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Core\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Core\Domain\Obras\ObraRepository;
use Ghi\Core\Services\Context;
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
     * Muestra los almacenes de la obra en contexto
     *
     * @return mixed
     */
    public function almacenes()
    {
        $almacenes = $this->almacenRepository->getAllPaginated();

        return view('reportes.almacenes')
            ->withAlmacenes($almacenes);
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
     * @param ObraRepository $obraRepository
     * @return Response
     * @throws ReglaNegocioException
     */
	public function store($idAlmacen, InicioActividadesRequest $request, ObraRepository $obraRepository)
	{
        try
        {
            if ($this->reporteRepository->existeEnFecha($idAlmacen, $request->get('fecha')))
            {
                throw new ReglaNegocioException('El reporte de actividades para la fecha indicada ya existe');
            }

            $obra = $obraRepository->getById(Context::getId());
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

            $reporte->obra()->associate($obra);
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

        $this->redirectSiReporteEstaCerrado($reporte);

        return view('reportes.edit')->withReporte($reporte);
	}


    /**
     * Update the specified resource in storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @param Request $request
     * @throws \Exception
     * @return Response
     */
	public function update($idAlmacen, $idReporte, Request $request)
	{
        try
        {
            DB::beginTransaction();

            $reporte = $this->reporteRepository->getById($idReporte);

            if ($reporte->cerrado)
            {
                throw new ReglaNegocioException('Este reporte no puede ser modificado por que ya esta cerrado.');
            }

            if ($request->has('cerrar'))
            {
                $reporte->cerrado = true;
            }

            $reporte->update($request->all());

            if ($request->has('actividades'))
            {
                foreach ($request->get('actividades', []) as $key => $horaInput)
                {
                    if ( ! $reporte->horas->contains($key))
                    {
                        continue;
                    }

                    $hora = $reporte->horas->find($key);

                    if (array_key_exists('borrar', $horaInput))
                    {
                        $hora->delete();
                        continue;
                    }

                    $hora->cantidad = $horaInput['cantidad'];
                    $hora->observaciones = $horaInput['observaciones'];

                    $hora->con_cargo = false;

                    if (array_key_exists('con_cargo', $horaInput))
                    {
                        $hora->con_cargo = true;
                    }

                    $hora->save();
                }
            }

            DB::commit();
        }
        catch(ReglaNegocioException $e)
        {
            Flash::error($e->getMessage());
        }
        catch (ModelNotFoundException $e)
        {
            Flash::error($e->getMessage());
        }
        finally
        {
            DB::rollback();

            return redirect()->back();
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

        $this->redirectSiReporteEstaCerrado($reporte);

        return view('reportes.cierre')->withReporte($reporte);
    }

    /**
     * Redirecciona a la pagina del reporte si este esta cerrado
     *
     * @param ReporteActividad $reporte
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectSiReporteEstaCerrado(ReporteActividad $reporte)
    {
        if ($reporte->cerrado)
        {
            Flash::error('Este reporte no puede ser modificado por que ya esta cerrado.');

            return redirect()->route('reportes.show', [$reporte->id]);
        }
    }

//    /**
//     * @param $idEquipo
//     * @param $fecha
//     * @param CierraReporteOperacionequest $request
//     * @return
//     */
//    public function cerrarReporte($idEquipo, $fecha, CierraReporteOperacionequest $request)
//    {
//        $reporte = $this->operacionService->cierraOperacion(
//            $idEquipo,
//            $fecha,
//            $request->get('horometro_final'),
//            $request->get('kilometraje_final')
//        );
//
//        \Flash::success("El reporte de operación del {$reporte->present()->fechaFormatoLocal} fue cerrado.");
//
//        return \Redirect::route('operacion.index', [$idEquipo]);
//    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $idAlmacen
     * @param $idReporte
     * @return Response
     */
	public function destroy($idAlmacen, $idReporte)
	{
        $reporte = $this->reporteRepository->getById($idReporte);

        $reporte->delete();

        Flash::message('El reporte fue eliminado.');

        return redirect()->route('reportes.index', [$idAlmacen]);
	}

}
