<?php

namespace Ghi\Http\Controllers\Conciliacion;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Conciliacion\Conciliacion;
use Ghi\Domain\Conciliacion\ConciliacionRepository;
use Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionPorConciliarEnPeriodoException;
use Ghi\Domain\Conciliacion\Exceptions\YaExisteConciliacionException;
use Ghi\Domain\Core\EmpresaRepository;
use Ghi\Domain\ReportesActividad\ReporteActividadRepository;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\Request;

class ConciliacionesController extends Controller
{
    /**
     * @var EmpresaRepository
     */
    private $empresaRepository;

    /**
     * @var AlmacenMaquinariaRepository
     */
    private $almacenRepository;

    /**
     * @var ConciliacionRepository
     */
    private $conciliacionRepository;

    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @param EmpresaRepository $empresaRepository
     * @param AlmacenMaquinariaRepository $almacenRepository
     * @param ConciliacionRepository $conciliacionRepository
     * @param ReporteActividadRepository $reporteRepository
     */
    public function __construct(
        EmpresaRepository $empresaRepository,
        AlmacenMaquinariaRepository $almacenRepository,
        ConciliacionRepository $conciliacionRepository,
        ReporteActividadRepository $reporteRepository
    ) {
        $this->middleware('auth');
        $this->middleware('context');

        $this->empresaRepository = $empresaRepository;
        $this->almacenRepository = $almacenRepository;
        $this->conciliacionRepository = $conciliacionRepository;
        $this->reporteRepository = $reporteRepository;
    }


    /**
     * Muestra una lista de empresas que rentan maquinaria
     *
     * @return mixed
     */
    public function proveedores()
    {
        $proveedores = $this->empresaRepository->getWithEntradasEquipo();

        return view('conciliacion.proveedores')->withProveedores($proveedores);
    }


    /**
     * Muestra una lista de almacenes relacionados con una empresa
     *
     * @param $idEmpresa
     * @return mixed
     */
    public function almacenes($idEmpresa)
    {
        $empresa = $this->empresaRepository->getById($idEmpresa);
        $almacenes = $this->almacenRepository->getByIdEmpresa($idEmpresa);

        return view('conciliacion.almacenes')
            ->withAlmacenes($almacenes)
            ->withEmpresa($empresa);
    }


    /**
     * Muestra una lita de conciliaciones de un almacen
     *
     * @param $idEmpresa
     * @param $idAlmacen
     * @return
     */
    public function index($idEmpresa, $idAlmacen)
    {
        $conciliaciones = $this->conciliacionRepository->getByAlmacen($idAlmacen);
        $empresa = $this->empresaRepository->getById($idEmpresa);
        $almacen = $this->almacenRepository->getById($idAlmacen);

        return view('conciliacion.index')
            ->withConciliaciones($conciliaciones)
            ->withEmpresa($empresa)
            ->withAlmacen($almacen);
    }


    /**
     * Muestra un formulario para conciliar un nuevo periodo
     *
     * @param $idEmpresa
     * @param $idAlmacen
     * @return $this
     */
    public function create($idEmpresa, $idAlmacen)
    {
        $empresa = $this->empresaRepository->getById($idEmpresa);
        $almacen = $this->almacenRepository->getById($idAlmacen);

        return view('conciliacion.create')
            ->withEmpresa($empresa)
            ->withAlmacen($almacen);
    }

    /**
     * Persiste una nueva conciliacion
     *
     * @param $idEmpresa
     * @param $idAlmacen
     * @param Request $request
     * @return mixed
     * @throws YaExisteConciliacionException
     * @throws NoExisteOperacionPorConciliarEnPeriodoException
     */
    public function store($idEmpresa, $idAlmacen, Request $request)
    {
        $fecha_inicial = $request->get('fecha_inicial');
        $fecha_final = $request->get('fecha_final');
        $observaciones = $request->get('observaciones');

        if ($this->conciliacionRepository->existeConciliacionEnPeriodo($idAlmacen, $fecha_inicial, $fecha_final)) {
            throw new YaExisteConciliacionException;
        }

        if (! $this->reporteRepository->existenHorasPorConciliarEnPeriodo($idAlmacen, $fecha_inicial, $fecha_final)) {
            throw new NoExisteOperacionPorConciliarEnPeriodoException;
        }

        $horas_contrato = $this->reporteRepository->getHorasContratoEnPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_efectivas = $this->reporteRepository->sumaHorasEfectivasPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_reparacion_mayor = $this->reporteRepository->sumaHorasReparacionMayorPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_reparacion_menor = $this->reporteRepository->sumaHorasReparacionMenorPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_mantenimiento = $this->reporteRepository->sumaHorasMantenimientoPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_ocio = $this->reporteRepository->sumaHorasOcioPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horometro_inicial = $this->reporteRepository->getHorometroIncialPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horometro_final = $this->reporteRepository->getHorometroFinalPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $horas_horometro = $this->reporteRepository->getHorasHorometroPorPeriodo($idAlmacen, $fecha_inicial, $fecha_final);
        $dias_con_operacion = $this->reporteRepository->diasConOperacionEnPeriodo($idAlmacen, $fecha_inicial, $fecha_final);

        $conciliacion = Conciliacion::generar(compact(
            'fecha_inicial',
            'fecha_final',
            'dias_con_operacion',
            'horas_contrato',
            'horas_efectivas',
            'horas_reparacion_mayor',
            'horas_reparacion_menor',
            'horas_mantenimiento',
            'horas_ocio',
            'horometro_inicial',
            'horometro_final',
            'horas_horometro',
            'observaciones'
        ));

        $almacen = $this->almacenRepository->getById($idAlmacen);
        $empresa = $this->empresaRepository->getById($idEmpresa);
        $usuario = auth()->user()->usuarioCadeco;

        $conciliacion->empresa()->associate($empresa);
        $conciliacion->almacen()->associate($almacen);
        $conciliacion->creadoPor()->associate($usuario);
        $conciliacion->save();

        return redirect()->route('conciliacion.edit', [$idEmpresa, $idAlmacen, $conciliacion->id]);
    }


    /**
     * Muestra un formulario para modificar una conciliacion
     *
     * @param $idEmpresa
     * @param $idAlmacen
     * @param $idConciliacion
     * @return mixed
     */
    public function edit($idEmpresa, $idAlmacen, $idConciliacion)
    {
        $conciliacion = $this->conciliacionRepository->getById($idConciliacion);

        return view('conciliacion.edit')->withConciliacion($conciliacion);
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

        return redirect()->route('conciliacion.edit', [$idProveedor, $idEquipo, $idConciliacion]);
    }
}
