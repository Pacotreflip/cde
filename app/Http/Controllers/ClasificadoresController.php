<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Clasificador;
use Ghi\Equipamiento\Articulos\ClasificadorRepository;

class ClasificadoresController extends Controller
{
    /**
     * @var ClasificadorRepository
     */
    protected $clasificadores;

    /**
     * @param ClasificadorRepository $clasificadores
     */
    public function __construct(ClasificadorRepository $clasificadores)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->clasificadores = $clasificadores;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clasificador      = null;
        $ancestros = [];

        if ($request->has('clasificador')) {
            $clasificador  = $this->clasificadores->getById($request->get('clasificador'));
            $descendientes = $clasificador->children()->defaultOrder()->get();
            $ancestros     = $clasificador->getAncestors();
        } else {
            $descendientes = $this->clasificadores->getNivelesRaiz();
        }

        return view('clasificadores.index')
            ->withClasificador($clasificador)
            ->withDescendientes($descendientes)
            ->withAncestros($ancestros);

        return view('clasificadores.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $clasificadores = [null => 'Inicio'] + $this->clasificadores->getAsList();

        return view('clasificadores.create')
            ->withClasificadores($clasificadores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Requests\CreateClasificadorRequest $request)
    {
        $clasificador = Clasificador::create($request->all());
        $parent = Clasificador::find($request->get('parent_id'));

        if ($parent) {
            $clasificador->appendTo($parent)->save();
        }

        Flash::success('El nuevo tipo de area fue agregado.');

        return redirect()->route('clasificadores.index', $parent ? ['clasificador' => $parent->id] : []);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $clasificador   = $this->clasificadores->getById($id);
        $ancestros      = $clasificador->getAncestors();
        $clasificadores = [null => 'Inicio'] + $this->clasificadores->getAsList();

        return view('clasificadores.edit')
            ->withClasificador($clasificador)
            ->withAncestros($ancestros)
            ->withClasificadores($clasificadores);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Requests\UpdateClasificadorRequest $request, $id)
    {
        $clasificador = $this->clasificadores->getById($id);
        $clasificador->fill($request->all());

        if ($request->has('parent_id')) {
            $parent = Clasificador::find($request->get('parent_id'));
            $clasificador->moverA($parent);
        }

        if ($request->has('move_up')) {
            $clasificador->up();
            return back();
        }

        if ($request->has('move_down')) {
            $clasificador->down();
            return back();
        }

        $this->clasificadores->save($clasificador);

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
        $clasificador = $this->clasificadores->getById($id);

        $this->clasificadores->delete($clasificador);

        return redirect()->route('clasificadores.index');
    }
}
