<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Foto;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Factory;
use Ghi\Equipamiento\Articulos\Familia;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Http\Requests\AgregaFotoRequest;
use Ghi\Equipamiento\Articulos\Clasificador;
use Ghi\Equipamiento\Articulos\TipoMaterial;
use Ghi\Http\Requests\CreateArticuloRequest;
use Ghi\Http\Requests\UpdateArticuloRequest;
use Ghi\Equipamiento\Articulos\MaterialRepository;

class ArticulosController extends Controller
{
    /**
     *
     * @var MaterialRepository
     */
    protected $materiales;

    /**
     * @param MaterialRepository $materiales
     */
    public function __construct(MaterialRepository $materiales)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->materiales = $materiales;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('buscar')) {
            $materiales = $this->materiales->buscar($request->get('buscar'));
        } else {
            $materiales = $this->materiales->getAllPaginated();
        }

        return view('articulos.index')
            ->withMateriales($materiales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $deafult_option = [null => 'Seleccione una opcion'];
        $unidades = $deafult_option + $this->materiales->getListaunidades();
        $familias = $this->materiales->getListaFamilias(TipoMaterial::TIPO_MATERIALES);
        $tipos    = $this->materiales->getListaTipoMateriales();
        $clasificadores = [];//$deafult_option + $this->materiales->getListaClasificadores();

        return view('articulos.create')
            ->withClasificadores($clasificadores)
            ->withUnidades($unidades)
            ->withFamilias($familias)
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateArticuloRequest $request, Factory $materiales)
    {
        if ($request->has('nueva_unidad')) {
            $unidad = Unidad::creaUnidad($request->get('nueva_unidad'), $request->get('nueva_unidad'));
        } else {
            $unidad = Unidad::where('unidad', $request->get('unidad'))->firstOrFail();
        }

        $familia = Familia::findOrFail($request->get('id_familia'));

        $material = $materiales->make(
            $request->get('descripcion'),
            $request->get('descripcion_larga'),
            $request->get('numero_parte'),
            $unidad,
            $unidad,
            new TipoMaterial(TipoMaterial::TIPO_MATERIALES)
        );

        if ($request->has('id_clasificador')) {
            $clasificador = Clasificador::findOrFail($request->get('id_clasificador'));
            $material->asignaClasificador($clasificador);
        }

        if ($request->hasFile('ficha_tecnica')) {
            $material->agregaFichaTecnica($request->file('ficha_tecnica'));
        }

        $material->asignaUnidad($unidad);
        $material->agregarEnFamilia($familia);
        $this->materiales->save($material);

        Flash::success('El articulo fue agregado');

        return redirect()->route('articulos.edit', [$material]);
    }

    /**
     * Agrega una foto a un articulo.
     *
     * @param  Request  $request
     * @return Response
     */
    public function agregaFoto(AgregaFotoRequest $request, $id)
    {
        $articulo = $this->materiales->getById($id);

        $file = $request->file('foto');
        $foto = Foto::conNombre($file->getClientOriginalName())->mover($file);
        $articulo->agregaFoto($foto);

        if ($request->ajax()) {
            return response('Foto agregada.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $material       = $this->materiales->getById($id);
        $unidades       = $this->materiales->getListaUnidades();
        $familias       = $this->materiales->getListaFamilias($material->tipo_material);
        $clasificadores = [];//$this->materiales->getListaClasificadores();

        return view('articulos.edit')
            ->withMaterial($material)
            ->withUnidades($unidades)
            ->withFamilias($familias)
            ->withClasificadores($clasificadores);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateArticuloRequest $request, $id)
    {
        $material     = $this->materiales->getById($id);
        $unidad       = Unidad::where('unidad', $request->get('unidad'))->firstOrFail();
        // $clasificador = Clasificador::findOrFail($request->get('clasificador_id'));
        $familia = Familia::findOrFail($request->get('id_familia'));

        $material->fill($request->all());
        $material->asignaUnidad($unidad);
        $material->agregarEnFamilia($familia);
        // $material->asignaClasificador($clasificador);

        if ($request->hasFile('ficha_tecnica')) {
            $material->agregaFichaTecnica($request->file('ficha_tecnica'));
        }

        $this->materiales->save($material);

        Flash::success('Los cambios fueron guardados');

        return back();
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
