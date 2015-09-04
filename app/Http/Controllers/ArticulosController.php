<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Foto;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Articulo;
use Ghi\Http\Requests\AgregaFotoRequest;
use Ghi\Equipamiento\Articulos\Clasificador;
use Ghi\Http\Requests\CreateArticuloRequest;
use Ghi\Http\Requests\UpdateArticuloRequest;
use Ghi\Equipamiento\Articulos\ArticuloRepository;

class ArticulosController extends Controller
{
    /**
     *
     * @var ArticuloRepository
     */
    protected $articulos;

    /**
     * @param ArticuloRepository $articulos
     */
    public function __construct(ArticuloRepository $articulos)
    {
        $this->middleware('auth');
        $this->articulos = $articulos;

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
        if ($request->has('busqueda')) {
            $articulos = $this->articulos->buscar($request->get('busqueda'));
        } else {
            $articulos = $this->articulos->getAllPaginated();
        }

        return view('articulos.index')
            ->withArticulos($articulos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $deafult_option = [null => 'Seleccione una opcion'];
        $clasificadores = $deafult_option + $this->articulos->getListaClasificadores();
        $unidades = $deafult_option + $this->articulos->getListaunidades();

        return view('articulos.create')
            ->withClasificadores($clasificadores)
            ->withUnidades($unidades);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CreateArticuloRequest $request)
    {
        $articulo = new Articulo($request->all());

        if (! $request->has('unidad')) {
            $unidad = Unidad::create(['codigo' => $request->get('nueva_unidad')]);
        } else {
            $unidad = Unidad::findOrFail($request->get('unidad'));
        }

        if (! $request->has('clasificador')) {
            $clasificador = Clasificador::create(['nombre' => $request->get('nuevo_clasificador')]);
        } else {
            $clasificador = CLasificador::findOrFail($request->get('clasificador'));
        }

        if ($request->hasFile('ficha_tecnica')) {
            $articulo->agregaFichaTecnica($request->file('ficha_tecnica'));
        }

        $articulo->asignaUnidad($unidad);
        $articulo->asignaClasificador($clasificador);
        $this->articulos->save($articulo);

        Flash::success('El articulo fue agregado');

        return redirect()->route('articulos.edit', [$articulo]);
    }

    /**
     * Agrega una foto a un articulo.
     *
     * @param  Request  $request
     * @return Response
     */
    public function agregaFoto(AgregaFotoRequest $request, $id)
    {
        $articulo = $this->articulos->getById($id);

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
        $articulo       = $this->articulos->getById($id);
        $unidades       = $this->articulos->getListaUnidades();
        $clasificadores = $this->articulos->getListaClasificadores();

        return view('articulos.edit')
            ->withArticulo($articulo)
            ->withUnidades($unidades)
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
        $articulo     = $this->articulos->getById($id);
        $unidad       = Unidad::findOrFail($request->get('unidad'));
        $clasificador = CLasificador::findOrFail($request->get('clasificador_id'));

        $articulo->fill($request->all());
        $articulo->asignaUnidad($unidad);
        $articulo->asignaClasificador($clasificador);

        if ($request->hasFile('ficha_tecnica')) {
            $articulo->agregaFichaTecnica($request->file('ficha_tecnica'));
        }

        $this->articulos->save($articulo);

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
