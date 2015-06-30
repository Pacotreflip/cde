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
        $opcionDefault = [null => 'Seleccione una opción'];

        $materiales = $opcionDefault + $this->materialRepository->getByTipoMaquinariaList();
        $propiedades = $opcionDefault + $this->repository->getPropiedadesList();
        $categorias = $opcionDefault + $this->repository->getCategoriasList();

        return view('almacenes.create')
            ->withMateriales($materiales)
            ->withPropiedades($propiedades)
            ->withCategorias($categorias);
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
        $categoria = $this->repository->getCategoriaById($request->get('id_categoria'));
        $propiedad = $this->repository->getPropiedadById($request->get('id_propiedad'));

        $almacen->id_obra = Context::getId();
        $almacen->tipo_almacen = AlmacenMaquinaria::TIPO_MAQUINARIA_CONTROL_INSUMOS;
        $almacen->material()->associate($material);
        $almacen->categoria()->associate($categoria);
        $almacen->propiedad()->associate($propiedad);

        $almacen->save();

        flash()->success('Un nuevo almacén fué registrado.');

        return redirect()->route('almacenes.show', [$almacen->id_almacen]);
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

        $materiales = $opcionDefault + $this->materialRepository->getByTipoMaquinariaList();
        $propiedades = $opcionDefault + $this->repository->getPropiedadesList();
        $categorias = $opcionDefault + $this->repository->getCategoriasList();

        return view('almacenes.edit')
            ->withAlmacen($almacen)
            ->withMateriales($materiales)
            ->withPropiedades($propiedades)
            ->withCategorias($categorias);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, ActualizaAlmacenMaquinariaRequest $request)
    {
        $almacen = $this->repository->getById($id);

        if ($request->has('id_propiedad')) {
            $propiedad = $this->repository->getPropiedadById($request->get('id_propiedad'));

            $almacen->propiedad()->associate($propiedad);
        }

        if ($request->has('id_categoria')) {
            $categoria = $this->repository->getCategoriaById($request->get('id_categoria'));

            $almacen->categoria()->associate($categoria);
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
