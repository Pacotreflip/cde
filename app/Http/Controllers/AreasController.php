<?php

namespace Ghi\Http\Controllers;

use Ghi\Area;
use Ghi\Subtipo;
use Ghi\Http\Requests;
use Ghi\Repositories\AreaRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AreasController extends Controller
{
    /**
     * @var AreaRepository
     */
    private $areas;

    /**
     * AreasController constructor.
     *
     * @param AreaRepository $areas
     */
    public function __construct(AreaRepository $areas)
    {
        $this->middleware('auth');
        $this->middleware('context');
        
        $this->areas = $areas;
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
        $area      = null;
        $ancestros = [];

        if ($request->has('area')) {
            $area = $this->areas->getById($request->get('area'));
            $descendientes = $area->children;
            $ancestros     = $area->getAncestors();
        } else {
            $descendientes = $this->areas->getNivelesRaiz();
        }

        return view('areas.index')
            ->withArea($area)
            ->withDescendientes($descendientes)
            ->withAncestros($ancestros);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $subtipos = $this->areas->getListaSubtipos();
        $areas    = $this->areas->getListaAreas();

        return view('areas.create')
            ->withAreas($areas)
            ->withSubtipos($subtipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateAreaRequest $request
     * @return Response
     */
    public function store(Requests\CreateAreaRequest $request)
    {
        $rango   = $request->get('rango_inicial');
        $subtipo = Subtipo::find($request->get('subtipo_id'));
        $parent  = Area::find($request->get('parent_id'));

        for ($i = 1; $i <= $request->get('cantidad', 1); $i++) {
            $area = new Area([
                'nombre'      => $request->get('nombre').' '.$rango,
                'clave'       => $request->get('clave'),
                'descripcion' => $request->get('descripcion'),
            ]);

            if ($subtipo) {
                $area->asignaSubtipo($subtipo);
            }

            if ($parent) {
                $area->moverA($parent);
            }

            $this->areas->save($area);
            $rango++;
        }

        return redirect()->route('areas.index', $parent ? ['area' => $parent->id] : []);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $area     = $this->areas->getById($id);
        $subtipos = $this->areas->getListaSubtipos();
        $areas    = $this->areas->getListaAreas();

        return view('areas.edit')
            ->withArea($area)
            ->withAreas($areas)
            ->withSubtipos($subtipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $area    = $this->areas->getById($id);
        $parent  = Area::find($request->get('parent_id'));
        $subtipo = Subtipo::find($request->get('subtipo_id'));

        $area->fill($request->all());

        if ($subtipo) {
            $area->asignaSubtipo($subtipo);
        }
        
        if ($parent) {
            $area->moverA($parent);
        }

        $this->areas->save($area);

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
        $area = $this->areas->getById($id);
        $isRoot = $area->isRoot();

        if (! $isRoot) {
            $parent_id = $area->parent->id;
        }

        $this->areas->delete($area);

        Flash::success('El area fue borrada.');

        return redirect()->route('areas.index', $isRoot ? [] : ['area' => $parent_id]);
    }
}
