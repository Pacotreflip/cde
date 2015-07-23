<?php

namespace Ghi\Http\Controllers\Conciliacion;

use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Conciliacion\ConciliacionRepository;
use Ghi\Domain\Conciliacion\Exceptions\YaExisteConciliacionException;
use Ghi\Domain\Conciliacion\GeneraConciliacion;
use Ghi\Domain\Core\EmpresaRepository;
use Ghi\Events\ConciliacionFueAprobada;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\Conciliacion\ActualizaConciliacionRequest;
use Ghi\Http\Requests\Conciliacion\RegistraConciliacionRequest;

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
     * @var GeneraConciliacion
     */
    private $generador;

    /**
     * @param EmpresaRepository $empresaRepository
     * @param AlmacenMaquinariaRepository $almacenRepository
     * @param ConciliacionRepository $conciliacionRepository
     * @param GeneraConciliacion $generador
     */
    public function __construct(
        EmpresaRepository $empresaRepository,
        AlmacenMaquinariaRepository $almacenRepository,
        ConciliacionRepository $conciliacionRepository,
        GeneraConciliacion $generador
    )
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->empresaRepository = $empresaRepository;
        $this->almacenRepository = $almacenRepository;
        $this->repository = $conciliacionRepository;
        $this->generador = $generador;
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
        $empresa   = $this->empresaRepository->getById($id_empresa);
        $almacenes = $this->almacenRepository->getByIdEmpresa($id_empresa);

        return view('conciliacion.almacenes')
            ->withAlmacenes($almacenes)
            ->withEmpresa($empresa);
    }


    /**
     * Muestra una lista de conciliaciones de un almacen
     *
     * @param $id_empresa
     * @param $id_almacen
     * @return
     */
    public function index($id_empresa, $id_almacen)
    {
        $conciliaciones = $this->repository->getByAlmacen($id_almacen);
        $empresa        = $this->empresaRepository->getById($id_empresa);
        $almacen        = $this->almacenRepository->getById($id_almacen);

        return view('conciliacion.index')
            ->withConciliaciones($conciliaciones)
            ->withEmpresa($empresa)
            ->withAlmacen($almacen);
    }


    /**
     * Muestra un formulario para conciliar un nuevo periodo
     *
     * @param $id_empresa
     * @param $id_almacen
     * @return $this
     */
    public function create($id_empresa, $id_almacen)
    {
        $empresa = $this->empresaRepository->getById($id_empresa);
        $almacen = $this->almacenRepository->getById($id_almacen);

        return view('conciliacion.create')
            ->withEmpresa($empresa)
            ->withAlmacen($almacen);
    }


    /**
     * Almacena una nueva conciliacion
     *
     * @param RegistraConciliacionRequest $request
     * @param $id_empresa
     * @param $id_almacen
     * @return \Illuminate\Http\RedirectResponse
     * @throws YaExisteConciliacionException
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionAprobadaEnPeriodoException
     */
    public function store(RegistraConciliacionRequest $request, $id_empresa, $id_almacen)
    {
        $fecha_inicial = $request->get('fecha_inicial');
        $fecha_final   = $request->get('fecha_final');
        $observaciones = $request->get('observaciones');

        if ($this->repository->existeConciliacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new YaExisteConciliacionException;
        }

        $conciliacion = $this->generador->generar($id_empresa, $id_almacen, $fecha_inicial, $fecha_final, $observaciones);

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
     * @throws \Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionAprobadaEnPeriodoException
     */
    public function edit($id_empresa, $id_almacen, $id)
    {
        $empresa      = $this->empresaRepository->getById($id_empresa);
        $almacen      = $this->almacenRepository->getById($id_almacen);
        $conciliacion = $this->repository->getById($id);

        if (! $conciliacion->aprobada) {
            $conciliacion = $this->generador->generar(
                $id_empresa,
                $id_almacen,
                $conciliacion->fecha_inicial,
                $conciliacion->fecha_final
            );
        }

        return view('conciliacion.edit')
            ->withEmpresa($empresa)
            ->withAlmacen($almacen)
            ->with('id', $id)
            ->withConciliacion($conciliacion);
    }

    /**
     * Persiste los cambios hechos a una conciliacion
     *
     * @param ActualizaConciliacionRequest $request
     * @param $id_empresa
     * @param $id_almacen
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ActualizaConciliacionRequest $request, $id_empresa, $id_almacen, $id)
    {
        $conciliacion = $this->repository->getById($id);

        if ($request->get('aprobar')) {
            $nuevaConciliacion = $this->generador->generar(
                $id_empresa,
                $id_almacen,
                $conciliacion->fecha_inicial,
                $conciliacion->fecha_final
            );
            $conciliacion->fill($nuevaConciliacion->getAttributes());
            $conciliacion->horas_efectivas_conciliadas  = $request->get('horas_efectivas_conciliadas');
            $conciliacion->horas_ocio_conciliadas       = $request->get('horas_ocio_conciliadas');
            $conciliacion->horas_reparacion_conciliadas = $request->get('horas_reparacion_conciliadas');
            $conciliacion->aprobar();
            event(new ConciliacionFueAprobada($conciliacion));
        }

        $this->repository->save($conciliacion);

        flash()->success('Los cambios fueron guardados');

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
