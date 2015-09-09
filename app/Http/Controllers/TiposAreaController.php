<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Tipo;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\CreateTipoRequest;
use Ghi\Http\Requests\UpdateTipoRequest;
use Ghi\Equipamiento\Areas\TipoAreaRepository;

class TiposAreaController extends Controller
{
    /**
     * @tipos TipoAreaRepository
     */
    protected $tipos;

    /**
     *
     * @param TipoAreaRepository $tipos
     */
    public function __construct(TipoAreaRepository $tipos)
    {
        $this->tipos = $tipos;

        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tipo      = null;
        $ancestros = [];

        if ($request->has('tipo')) {
            $tipo = $this->tipos->getById($request->get('tipo'));
            $descendientes = $tipo->children()->defaultOrder()->get();
            $ancestros     = $tipo->getAncestors();
        } else {
            $descendientes = $this->tipos->getNivelesRaiz();
        }

        return view('tipos.index')
            ->withTipo($tipo)
            ->withDescendientes($descendientes)
            ->withAncestros($ancestros);

        return view('tipos.index')->withTipos($tipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = [null => 'Inicio'] + $this->tipos->getListaTipos();

        return view('tipos.create')
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTipoRequest $request
     * @return Response
     */
    public function store(CreateTipoRequest $request)
    {
        $parent = Tipo::find($request->get('parent_id'));

        $tipo = Tipo::create($request->all(), $parent);

        Flash::success('El nuevo tipo de area fue agregado.');

        return redirect()->route('tipos.index', $parent ? ['tipo' => $parent->id] : []);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo      = $this->tipos->getById($id);
        $tipos     = [null => 'Inicio'] + $this->tipos->getListaTipos();
        $ancestros = $tipo->getAncestors();

        return view('tipos.edit')
            ->withTipo($tipo)
            ->withAncestros($ancestros)
            ->withTipos($tipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTipoRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateTipoRequest $request, $id)
    {
        $tipo   = Tipo::findOrFail($id);
        $parent = Tipo::find($request->get('parent_id'));

        $tipo->fill($request->all());

        $tipo->moverA($parent);

        if ($request->has('move_up')) {
            $tipo->up();
            return redirect()->back();
        }

        if ($request->has('move_down')) {
            $tipo->down();
            return back();
        }

        $this->tipos->save($tipo);

        Flash::success('Los cambios fueron guardados.');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tipo = Tipo::findOrFail($id);
        $tipo->delete();

        Flash::message('El tipo de area fue borrado.');

        return redirect()->route('tipos.index');
    }
}
