<?php namespace Ghi\Conciliacion\Http\Controllers;

use Ghi\Conciliacion\Domain\Commands\CerrarPeriodoCommand;
use Ghi\Conciliacion\Domain\Commands\GenerarPeriodoCommand;
use Ghi\Conciliacion\Domain\ConciliacionService;
use Ghi\Conciliacion\Domain\Periodos\PeriodoRepository;
use Ghi\Conciliacion\Domain\ProveedorRepository;
use Ghi\Core\App\Facades\Context;
use Ghi\SharedKernel\Contracts\EquipoRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laracasts\Commander\CommanderTrait;

class ConciliacionController extends Controller {

	use CommanderTrait;

	/**
	 * @var ProveedorRepository
	 */
	private $proveedorRepository;

    /**
	 * @var EquipoRepository
	 */
	private $equipoRepository;

	/**
	 * @var PeriodoRepository
	 */
	private $periodoRepository;

	/**
	 * @param ProveedorRepository $proveedorRepository
	 * @param EquipoRepository $equipoRepository
	 * @param PeriodoRepository $periodoRepository
     */
	function __construct(ProveedorRepository $proveedorRepository, EquipoRepository $equipoRepository, PeriodoRepository $periodoRepository)
	{
		$this->proveedorRepository = $proveedorRepository;
		$this->equipoRepository = $equipoRepository;
		$this->periodoRepository = $periodoRepository;
	}


	/**
	 * @return mixed
     */
	public function proveedores()
	{
		$proveedores = $this->proveedorRepository->findAll(Context::getId());

		return view('maquinaria.conciliacion.proveedores')->withProveedores($proveedores);
	}


	/**
	 * @param $idProveedor
	 * @return mixed
     */
	public function equipos($idProveedor)
	{
		$equipos = $this->equipoRepository->findByIdProveedor(Context::getId(), $idProveedor);

		$proveedor = $this->proveedorRepository->findById($idProveedor);

		return view('maquinaria.conciliacion.equipos')
			->withEquipos($equipos)
			->withProveedor($proveedor);
	}


	/**
	 * @param $idProveedor
	 * @param $idEquipo
	 * @return
	 */
	public function index($idProveedor, $idEquipo)
	{
		$periodos = $this->periodoRepository->findByEquipo(Context::getId(), $idProveedor, $idEquipo);

		$proveedor = $this->proveedorRepository->findById($idProveedor);

		$equipo = $this->equipoRepository->findById($idEquipo);

		return view('maquinaria.conciliacion.index')
			->withPeriodos($periodos)
			->withProveedor($proveedor)
			->withEquipo($equipo);
	}


	/**
	 * @param $idProveedor
	 * @param $idEquipo
	 * @return $this
	 */
	public function create($idProveedor, $idEquipo)
	{
		return view('maquinaria.conciliacion.create')
			->with('idProveedor', $idProveedor)
			->with('idEquipo', $idEquipo);
	}

	/**
	 * @param $idProveedor
	 * @param $idEquipo
	 * @param Request $request
	 * @return mixed
     */
	public function store($idProveedor, $idEquipo, Request $request)
	{
		$idObra = Context::getId();
		$fechaInicial = $request->get('fecha_inicial');
		$fechaFinal = $request->get('fecha_final');
		$observaciones = $request->get('observaciones');
		$usuario = \Auth::user()->usuario;

		$periodo = $this->execute(GenerarPeriodoCommand::class,
			compact('idObra', 'idProveedor', 'idEquipo', 'fechaInicial', 'fechaFinal', 'observaciones', 'usuario')
		);

		return \Redirect::route('conciliacion.edit', [$idProveedor, $idEquipo, $periodo->id]);
	}


	/**
	 * @param $idProveedor
	 * @param $idEquipo
	 * @param $idConciliacion
	 * @return mixed
     */
	public function edit($idProveedor, $idEquipo, $idConciliacion)
	{
		$periodo = $this->periodoRepository->findById($idConciliacion);

		return view('maquinaria.conciliacion.edit')
			->withPeriodo($periodo);
	}

	/**
	 * @param $idProveedor
	 * @param $idEquipo
	 * @param $idConciliacion
	 * @param Request $request
	 * @return mixed
     */
	public function update($idProveedor, $idEquipo, $idConciliacion, Request $request)
	{
		// validate data

		$id = $idConciliacion;
		$horasEfectivas = $request->get('horas_efectivas');
		$horasReparacionMayor = $request->get('horas_reparacion_mayor');
		$horasOcio = $request->get('horas_ocio');

		$this->execute(CerrarPeriodoCommand::class,
			compact('id', 'horasEfectivas', 'horasReparacionMayor', 'horasOcio')
		);

		return \Redirect::route('conciliacion.edit', [$idProveedor, $idEquipo, $idConciliacion]);
	}

}
