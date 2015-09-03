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

class ArticulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }
    /**
     * Obtiene todos los articulos paginados
     *
     * @param int $howMany
     * @return mixed
     */
    protected function getAllPaginated($howMany = 30)
    {
        return Articulo::orderBy('nombre')->paginate($howMany);
    }

    /**
     * Busca un articulo por su id
     *
     * @return Articulo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Articulo::findOrFail($id);
    }

    /**
     * Busqueda de articulos
     *
     * @param $busqueda
     * @param int $howMany
     * @return mixed
     */
    protected function buscar($busqueda, $howMany = 30)
    {
        return Articulo::where('nombre', 'LIKE', '%'.$busqueda.'%')
            ->orWhere('numero_parte', 'LIKE', '%'.$busqueda.'%')
            ->orWhere('descripcion', 'LIKE', '%'.$busqueda.'%')
            ->paginate($howMany);
    }

    /**
     * Obtiene una lista de unidades como arreglo
     *
     * @return array
     */
    public function getListaUnidades()
    {
        return Unidad::orderBy('codigo')->lists('codigo', 'codigo')->all();
    }

    /**
     * Obtiene una lista de clasificadores como arreglo
     *
     * @return array
     */
    public function getListaClasificadores()
    {
        return Clasificador::orderBy('nombre')->lists('nombre', 'id')->all();
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
            $articulos = $this->buscar($request->get('busqueda'));
        } else {
            $articulos = $this->getAllPaginated();
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
        $clasificadores = $default_option + $this->getListaClasificadores();
        $unidades = $default_option + $this->getListaunidades();

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

        $articulo->asociaConUnidad($unidad);
        $articulo->asociaConClasificador($clasificador);
        $articulo->save();

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
        $articulo = $this->getById($id);

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
        $articulo = $this->getById($id);
        $unidades = $this->getListaUnidades();
        $clasificadores = $this->getListaClasificadores();

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
        $articulo = $this->getById($id);

        $articulo->fill($request->all());
        $articulo->unidad = $request->get('unidad');
        $articulo->clasificador_id = $request->get('clasificador_id');

        if ($request->hasFile('ficha_tecnica')) {
            $articulo->agregaFichaTecnica($request->file('ficha_tecnica'));
        }

        $articulo->save();

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
