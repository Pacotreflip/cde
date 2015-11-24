<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\AreaTipo;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Areas\AreaRepository;
use Ghi\Equipamiento\Areas\AreasTipo;

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
    public function __construct(AreaRepository $areas, AreasTipo $tipos)
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
        $tipos = [null => 'Ninguno'] + $this->tipos->getListaUltimosNiveles();
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
        $rango = $request->get('rango_inicial');
        $tipo = AreaTipo::find($request->get('tipo_id'));
        $parent = Area::find($request->get('parent_id'));
        $cantidad_a_crear = $request->get('cantidad', 1);

        for ($i = 1; $i <= $cantidad_a_crear; $i++) {
            $nombre = $request->get('nombre');

            if ($cantidad_a_crear > 1) {
                $nombre .= ' '.$rango;
            }

            $area = new Area([
                'nombre' => $nombre,
                'clave' => $request->get('clave', $tipo ? $tipo->clave : ''),
                'descripcion' => $request->get('descripcion'),
            ]);

            $area->obra()->associate($this->getObraEnContexto());

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
        $tipos = [null => 'Ninguno'] + $this->tipos->getListaUltimosNiveles();
        $areas = $this->areas->getListaAreas();

        // dd($this->estadisticaMateriales($area));

        return view('areas.edit')
            ->withArea($area)
            ->withAreas($areas)
            ->withTipos($tipos);
    }

    /**
     * [estadisticaMateriales description]
     * @param  [type] $area [description]
     * @return [type]       [description]
     */
    protected function estadisticaMateriales($area)
    {
        $inventarios = $area->inventarios;
        $requerimientos = $area->tipo->materiales;
        $lista_materiales = $requerimientos->merge($inventarios);

        $materiales = Material::whereIn('id_material', $lista_materiales->lists('id_material'))->get();

        $estadisticas = $materiales->map(function ($material, $key) use ($requerimientos, $inventarios) {
            $material->cantidad_requerida  = 0;
            $material->cantidad_almacenada = 0;

            if (! $requerimientos->where('id_material', $material->id_material)->isEmpty()) {
                $material->cantidad_requerida = $requerimientos->where('id_material', $material->id_material)->first()->pivot->cantidad;
            }

            if (! $inventarios->where('id_material', $material->id_material)->isEmpty()) {
                $material->cantidad_almacenada = $inventarios->where('id_material', $material->id_material)->first()->cantidad;
            }

            return $material;
        });

        return $estadisticas;
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
        $tipo   = AreaTipo::find($request->get('tipo_id'));

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
