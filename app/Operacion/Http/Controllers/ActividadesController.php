<?php namespace Ghi\Operacion\Http\Controllers;

use Ghi\Core\Domain\Conceptos\ConceptoRepository;
use Ghi\Operacion\Http\Requests\ReportarHorasRequest;
use Ghi\Operacion\Domain\Commands\ReportarHorasCommand;
use Ghi\Maquinaria\Domain\Operacion\OperacionService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Commander\CommanderTrait;

class ActividadesController extends Controller {

    use CommanderTrait;

	/**
	 * @var OperacionService
     */
	private $operacionService;

	/**
	 * @var ConceptoRepository
	 */
	private $conceptoRepository;

	/**
	 * @param OperacionService $operacionService
	 * @param ConceptoRepository $conceptoRepository
	 */
	function __construct(OperacionService $operacionService, ConceptoRepository $conceptoRepository)
	{
        $this->middleware('auth');
        $this->middleware('context');

		$this->operacionService = $operacionService;
		$this->conceptoRepository = $conceptoRepository;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @param $idEquipo
	 * @param $fecha
	 * @return Response
	 */
	public function create($idEquipo, $fecha)
	{
		$tipoHora = $this->operacionService->getTiposHoraList();

		$reporte = $this->operacionService->findByFecha($idEquipo, $fecha);

//		$conceptos = [null => 'Elija una opción'] + $this->conceptoRepository->getConceptosList(TenantContext::getTenantId());

		return view('maquinaria.horas.create', compact('reporte', 'tipoHora', 'conceptos'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param $idEquipo
	 * @param $fecha
	 * @param ReportarHorasRequest $request
	 * @return Response
	 */
	public function store($idEquipo, $fecha, ReportarHorasRequest $request)
	{
        $idTipoHora = $request->get('tipo_hora');
        $cantidad = $request->get('cantidad');
        $idConcepto = $request->get('id_concepto');
        $conCargo = $request->get('con_cargo');
        $observaciones = $request->get('observaciones');
        $usuario = \Auth::user()->usuario;

        $this->execute(ReportarHorasCommand::class, compact(
            'idEquipo', 'fecha', 'idTipoHora', 'cantidad', 'idConcepto', 'conCargo', 'observaciones', 'usuario')
        );

		\Flash::success('Las horas fueron reportadas.');

		return \Redirect::route('operacion.show', [$idEquipo, $fecha]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $idEquipo
	 * @param $fecha
	 * @param $idHora
	 * @return Response
	 */
	public function destroy($idEquipo, $fecha, $idHora)
	{
		$this->operacionService->borraHora($idEquipo, $fecha, $idHora);

		return Redirect::route('operacion.show', [$idEquipo, $fecha]);
	}

}
