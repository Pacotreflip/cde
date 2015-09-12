<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\Tipo;
use Ghi\Equipamiento\Areas\AreaRepository;
use Ghi\Equipamiento\Areas\TipoAreaRepository;

class AreasController extends Controller
{
    /**
     * @var AreaRepository
     */
    private $areas;
    protected $tipos;

    /**
     * AreasController constructor.
     *
     * @param AreaRepository $areas
     */
    public function __construct(AreaRepository $areas, TipoAreaRepository $tipos)
    {
        $this->middleware('auth');
        $this->middleware('context');
        
        $this->areas = $areas;
        $this->tipos = $tipos;

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
            $descendientes = $area->children()->defaultOrder()->get();
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
        $tipos = [null => 'Ninguno'] + $this->tipos->getListaTipos();
        $areas = $this->areas->getListaAreas();

        return view('areas.create')
            ->withAreas($areas)
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateAreaRequest $request
     * @return Response
     */
    public function store(Requests\CreateAreaRequest $request)
    {
        $rango  = $request->get('rango_inicial');
        $tipo   = Tipo::find($request->get('tipo_id'));
        $parent = Area::find($request->get('parent_id'));

        for ($i = 1; $i <= $request->get('cantidad', 1); $i++) {
            $area = new Area([
                'nombre'      => $request->get('nombre').' '.$rango,
                'clave'       => $request->get('clave'),
                'descripcion' => $request->get('descripcion'),
            ]);

            if ($tipo) {
                $area->asignaTipo($tipo);
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
        $area  = $this->areas->getById($id);
        $tipos = [null => 'Ninguno'] + $this->tipos->getListaTipos();
        $areas = $this->areas->getListaAreas();

        return view('areas.edit')
            ->withArea($area)
            ->withAreas($areas)
            ->withTipos($tipos);
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
        $area   = $this->areas->getById($id);
        $parent = Area::find($request->get('parent_id'));
        $tipo   = Tipo::find($request->get('tipo_id'));

        $area->fill($request->all());

        if ($tipo) {
            $area->asignaTipo($tipo);
        }
        
        if ($parent) {
            $area->moverA($parent);
        }

        if ($request->has('move_up')) {
            $area->up();
            return back();
        }

        if ($request->has('move_down')) {
            $area->down();
            return back();
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
