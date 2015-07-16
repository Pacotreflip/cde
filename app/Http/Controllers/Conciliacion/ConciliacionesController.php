<?php

namespace Ghi\Http\Controllers\Conciliacion;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Conciliacion\ConciliacionRepository;
use Ghi\Domain\Conciliacion\GeneraConciliacion;
use Ghi\Domain\Core\EmpresaRepository;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\Conciliacion\ActualizaConciliacionRequest;
use Ghi\Http\Requests\Conciliacion\RegistraConciliacionRequest;
use Illuminate\Http\Request;

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
    private $repository;

    /**
     * @param EmpresaRepository $empresaRepository
     * @param AlmacenMaquinariaRepository $almacenRepository
     * @param ConciliacionRepository $conciliacionRepository
     */
    public function __construct(
        EmpresaRepository $empresaRepository,
        AlmacenMaquinariaRepository $almacenRepository,
        ConciliacionRepository $conciliacionRepository
    )
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->empresaRepository = $empresaRepository;
        $this->almacenRepository = $almacenRepository;
        $this->repository = $conciliacionRepository;
    }


    /**
     * Muestra una lista de empresas que rentan maquinaria
     *
     * @return mixed
     */
    public function showProveedores()
    {
        $proveedores = $this->empresaRepository->getProveedoresMaquinaria();

        return view('conciliacion.proveedores')->withProveedores($proveedores);
    }


    /**
     * Muestra una lista de almacenes relacionados con una empresa
     *
     * @param $id_empresa
     * @return mixed
     */
    public function showAlmacenes($id_empresa)
    {
        $empresa = $this->empresaRepository->getById($id_empresa);
        $almacenes = $this->almacenRepository->getByIdEmpresa($id_empresa);

        return view('conciliacion.almacenes')
            ->withAlmacenes($almacenes)
            ->withEmpresa($empresa);
    }


    /**
     * Muestra una lista de conciliaciones de un almacen
     *
     * @param $idEmpresa
     * @param $idAlmacen
     * @return
     */
    public function index($idEmpresa, $idAlmacen)
    {
        $conciliaciones = $this->repository->getByAlmacen($idAlmacen);
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
     * Almacena una nueva conciliacion
     *
     * @param RegistraConciliacionRequest $request
     * @param GeneraConciliacion $generador
     * @param $id_empresa
     * @param $id_almacen
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Ghi\Domain\Conciliacion\NoExisteOperacionAprobadaEnPeriodoException
     * @throws \Ghi\Domain\Conciliacion\YaExisteConciliacionException
     */
    public function store(RegistraConciliacionRequest $request, GeneraConciliacion $generador, $id_empresa, $id_almacen)
    {
        $fecha_inicial = $request->get('fecha_inicial');
        $fecha_final   = $request->get('fecha_final');
        $observaciones = $request->get('observaciones');

        $conciliacion = $generador->generar($id_empresa, $id_almacen, $fecha_inicial, $fecha_final, $observaciones);
        $this->repository->save($conciliacion);

        return redirect()->route('conciliacion.edit', [$id_empresa, $id_almacen, $conciliacion]);
    }


    /**
     * Muestra un formulario para modificar una conciliacion
     *
     * @param $id_empresa
     * @param $id_almacen
     * @param $id
     * @return mixed
     */
    public function edit($id_empresa, $id_almacen, $id)
    {
        $empresa      = $this->empresaRepository->getById($id_empresa);
        $almacen      = $this->almacenRepository->getById($id_almacen);
        $conciliacion = $this->repository->getById($id);

        return view('conciliacion.edit')
            ->withEmpresa($empresa)
            ->withAlmacen($almacen)
            ->withConciliacion($conciliacion);
    }


    public function update(ActualizaConciliacionRequest $request, $id_empresa, $id_almacen, $id)
    {
        $conciliacion     = $this->repository->getById($id);

        $conciliacion->horas_efectivas_conciliadas  = $request->get('horas_efectivas_conciliadas');
        $conciliacion->horas_ocio_conciliadas       = $request->get('horas_ocio_conciliadas');
        $conciliacion->horas_reparacion_conciliadas = $request->get('horas_reparacion_conciliadas');

        if ($request->get('cerrar')) {
            $conciliacion->cerrar();
        }

        $this->repository->save($conciliacion);

        flash()->success('La conciliación fue actualizada.');

        return redirect()->route('conciliacion.edit', [$id_empresa, $id_almacen, $id]);
    }


    /**
     * Elimina una conciliacion
     *
     * @param $id_empresa
     * @param $id_almacen
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id_empresa, $id_almacen, $id)
    {
        $conciliacion = $this->repository->getById($id);

        $conciliacion->delete();

        flash('La conciliación fue eliminada.');

        return redirect()->route('conciliacion.index', [$id_empresa, $id_almacen]);
    }
}
