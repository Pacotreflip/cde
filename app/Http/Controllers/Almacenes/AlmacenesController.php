<?php

namespace Ghi\Http\Controllers\Almacenes;

use Ghi\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Core\MaterialRepository;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\Almacenes\ActualizaAlmacenMaquinariaRequest;
use Ghi\Http\Requests\Almacenes\RegistraAlmacenMaquinariaRequest;
use Ghi\Domain\Core\Facades\Context;

class AlmacenesController extends Controller
{
    /**
     * @var AlmacenMaquinariaRepository
     */
    private $repository;

    /**
     * @var MaterialRepository
     */
    private $materialRepository;

    /**
     * @param AlmacenMaquinariaRepository $repository
     * @param MaterialRepository $materialRepository
     */
    public function __construct(
        AlmacenMaquinariaRepository $repository,
        MaterialRepository $materialRepository
    ) {
        $this->middleware('auth');
        $this->middleware('context');

        $this->repository = $repository;
        $this->materialRepository = $materialRepository;
    }

    /**
     * Muestra una lista de almacenes de maquinaria de la obra.
     *
     * @return Response
     */
    public function index()
    {
        $almacenes = $this->repository->getAllPaginated();

        return view('almacenes.index')->withAlmacenes($almacenes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $opcionDefault   = [null => 'Seleccione una opción'];
        $materiales      = $opcionDefault + $this->materialRepository->getByTipoMaquinariaList();
        $propiedades     = $opcionDefault + $this->repository->getPropiedadesList();
        $clasificaciones = $opcionDefault + $this->repository->getClasificacionesList();

        return view('almacenes.create')
            ->withMateriales($materiales)
            ->withPropiedades($propiedades)
            ->withClasificaciones($clasificaciones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RegistraAlmacenMaquinariaRequest $request
     * @return Response
     */
    public function store(RegistraAlmacenMaquinariaRequest $request)
    {
        $almacen = new AlmacenMaquinaria($request->all());

        $material = $this->materialRepository->getById($request->get('id_material'));

        if ($request->has('propiedad')) {
            $almacen->propiedad = $request->get('propiedad');
        }

        if ($request->has('clasificacion')) {
            $almacen->clasificacion = $request->get('clasificacion');
        }

        $almacen->id_obra = Context::getId();
        $almacen->tipo_almacen = AlmacenMaquinaria::TIPO_MAQUINARIA_CONTROL_INSUMOS;
        $almacen->material()->associate($material);

        $almacen->save();

        flash()->success('Un nuevo almacén fué registrado.');

        return redirect()->route('almacenes.show', [$almacen]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $almacen = $this->repository->getById($id);

        return view('almacenes.show')->withAlmacen($almacen);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $almacen = $this->repository->getById($id);

        $opcionDefault = [null => 'Seleccione una opción'];
        $materiales      = $opcionDefault + $this->materialRepository->getByTipoMaquinariaList();
        $propiedades     = $opcionDefault + $this->repository->getPropiedadesList();
        $clasificaciones = $opcionDefault + $this->repository->getClasificacionesList();

        return view('almacenes.edit')
            ->withAlmacen($almacen)
            ->withMateriales($materiales)
            ->withPropiedades($propiedades)
            ->withClasificaciones($clasificaciones);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param ActualizaAlmacenMaquinariaRequest $request
     * @return Response
     */
    public function update($id, ActualizaAlmacenMaquinariaRequest $request)
    {
        $almacen = $this->repository->getById($id);

        if ($request->has('propiedad')) {
            $almacen->propiedad = $request->get('propiedad');
        }

        if ($request->has('categoria')) {
            $almacen->categoria = $request->get('categoria');
        }

        $almacen->update($request->only([
            'numero_economico',
            'descripcion'
        ]));

        flash()->success('Los cambios fueron guardados.');

        return redirect()->route('almacenes.show', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
