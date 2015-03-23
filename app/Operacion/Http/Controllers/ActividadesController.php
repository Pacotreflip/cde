<?php namespace Ghi\Operacion\Http\Controllers;

use Ghi\Core\App\Exceptions\ReglaNegocioException;
use Ghi\Core\Domain\Conceptos\ConceptoRepository;
use Ghi\Operacion\Domain\Actividad;
use Ghi\Operacion\Domain\Exceptions\ConceptoNoEsMedibleException;
use Ghi\Operacion\Domain\ReporteActividadRepository;
use Ghi\Operacion\Http\Requests\ReportarActividadRequest;
use Ghi\Operacion\Domain\Commands\ReportarHorasCommand;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

//use Laracasts\Commander\CommanderTrait;

class ActividadesController extends Controller {

    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @var ConceptoRepository
     */
    private $conceptoRepository;

    /**
     * @param ReporteActividadRepository $reporteRepository
     * @param ConceptoRepository $conceptoRepository
     */
	function __construct(ReporteActividadRepository $reporteRepository, ConceptoRepository $conceptoRepository)
	{
        $this->middleware('auth');
        $this->middleware('context');

        $this->reporteRepository = $reporteRepository;
        $this->conceptoRepository = $conceptoRepository;
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
		$tiposHora = $this->reporteRepository->getTiposHoraList();

		$reporte = $this->reporteRepository->getById($idReporte);

//		$conceptos = [null => 'Elija una opción'] + $this->conceptoRepository->getConceptosList(TenantContext::getTenantId());

		return view('actividades.create', compact('reporte', 'tiposHora'));
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
        try
        {
            $reporte = $this->reporteRepository->getById($idReporte);

            $actividad = new Actividad($request->all());
            $actividad->id_tipo_hora = $request->get('tipo_hora');
            $actividad->creadoPor()->associate(Auth::user());

            if ($request->has('id_concepto'))
            {
                $concepto = $this->conceptoRepository->getById($request->get('id_concepto'));

                if ( ! $concepto->esMedible())
                {
                    throw new ConceptoNoEsMedibleException;
                }

                $actividad->destino()->associate($concepto);
            }

            $actividad->reportarEn($reporte);
        }
        catch(ReglaNegocioException $e)
        {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

		Flash::success('La actividad fue agregada al reporte.');

		return redirect()->route('reportes.show', [$idAlmacen, $idReporte]);
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

        if ($reporte->cerrado)
        {
            Flash::error('Este reporte no puede ser modificado');

            return redirect()->back();
        }

		$reporte->actividades()->find($idActividad)->delete();

		return redirect()->route('reportes.show', [$idAlmacen, $idReporte]);
	}

}
