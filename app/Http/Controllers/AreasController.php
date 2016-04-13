<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\AreaTipo;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Equipamiento\Areas\AreasTipo;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Areas\Almacen;
use Ghi\Http\Requests\UpdateAreaRequest;
class AreasController extends Controller
{
    protected $areas;

    protected $areas_tipo;

    /**
     * AreasController constructor.
     *
     * @param Areas     $areas
     * @param AreasTipo $areas_tipo
     */
    public function __construct(Areas $areas, AreasTipo $areas_tipo)
    {
        $this->middleware('auth');
        $this->middleware('context');
        
        $this->areas = $areas;
        $this->areas_tipo = $areas_tipo;

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
        $area = null;
        $ancestros = [];

        if ($request->has('area')) {
            $area = $this->areas->getById($request->get('area'));
            $descendientes = $area->children()->defaultOrder()->get();
            $ancestros = $area->getAncestors();
        } else {
            $descendientes = $this->areas->getNivelesRaiz();
        }

        $areas_tipo = $this->areasTipoDescendientes($area);

        return view('areas.index')
            ->withArea($area)
            ->withDescendientes($descendientes)
            ->withAncestros($ancestros)
            ->withAreasTipo($areas_tipo);
    }

    /**
     * Areas tipo asignadas a los descendientes de un area.
     * 
     * @param  null|Area $area
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    protected function areasTipoDescendientes($area)
    {
        if (is_null($area)) {
            return [];
        }

        $ids_areas_tipo = $area->descendientesConAreaTipo()->keys();

        return AreaTipo::whereIn('id', $ids_areas_tipo)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = [null => 'Ninguno'] + $this->areas_tipo->getListaUltimosNiveles();
        $areas = $this->areas->getListaAreas();
        $almacenes = $this->areas->getListaAlmacenes();

        return view('areas.create')
        ->with("almacenes",$almacenes)
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
        //dd($parent->ruta);

        for ($i = 1; $i <= $cantidad_a_crear; $i++) {
            
            $nombre = $request->get('nombre');
            
            if($cantidad_a_crear == 1 && $request->almacen_id == 0){
                $nombre_almacen = ($parent)?strtoupper(substr(str_replace(" / ", " / ", $parent->ruta),0,(255-(strlen($nombre)))) . " / " .$nombre):strtoupper($nombre);
                //dd($nombre_almacen);
                $almacen = new Almacen([
                    "descripcion"=>$nombre_almacen,
                    "tipo_almacen"=>"0",
                ]);
                $almacen->obra()->associate($this->getObraEnContexto());
                $almacen->save();
            }elseif($cantidad_a_crear == 1 && $request->almacen_id > 0){
                $almacen = Almacen::findOrFail($request->almacen_id);
            }else{
                $almacen = false;
            }

            if ($cantidad_a_crear > 1) {
                $nombre .= ' '.$rango;
            }

            $area = new Area([
                'nombre' => $nombre,
                'clave' => $request->get('clave', $tipo ? $tipo->clave : ''),
                'descripcion' => $request->get('descripcion'),
            ]);

            $area->obra()->associate($this->getObraEnContexto());
            if($almacen){
                $area->almacen()->associate($almacen);
            }

            if ($tipo) {
                $area->asignaTipo($tipo);
            }

            if ($parent) {
                $area->moverA($parent);
            }
            
            $this->areas->save($area);
            
            if($tipo){
                $materiales_requeridos = $area->getArticuloRequeridoDesdeAreaTipo($tipo);
                $area->materialesRequeridos()->saveMany($materiales_requeridos);
            }
            
            
            
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
        $area = $this->areas->getById($id);
        $tipos = [null => 'Ninguno'] + $this->areas_tipo->getListaUltimosNiveles();
        $areas = $this->areas->getListaAreas();
        $almacenes = $this->areas->getListaAlmacenes();

        // dd($this->estadisticaMateriales($area));

        return view('areas.edit')
            ->with("almacenes",$almacenes)
            ->withArea($area)
            ->withAreas($areas)
            ->withTipos($tipos);
    }

    /**
     * [estadisticaMateriales description]
     *
     * @param  [type] $area
     * @return [type]
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
     * @param  int      $id
     * @return Response
     */
    public function update(UpdateAreaRequest $request, $id)
    {
        $area = $this->areas->getById($id);
        $parent = Area::find($request->get('parent_id'));
        $tipo = AreaTipo::find($request->get('tipo_id'));
        $nombre = $request->nombre;
        $almacen_actual = $area->almacen;
        
        
        if($request->almacen_id == 0){
            $nombre_almacen = ($parent)?strtoupper(substr(str_replace(" / ", " / ", $parent->ruta),0,(255-(strlen($nombre)))) . " / " .$nombre):strtoupper($nombre);
            $almacenes = Almacen::where("descripcion", $nombre_almacen)->first();
            if($almacenes){
                throw new \Exception("Seleccione un almacén de la lista que coincida con el nombre del área.");
            }
            $almacen = new Almacen([
                "descripcion"=>$nombre_almacen,
                "tipo_almacen"=>"0",
            ]);
            $almacen->obra()->associate($this->getObraEnContexto());
            $almacen->save();
        }elseif($request->almacen_id > 0){
            $almacen = Almacen::findOrFail($request->almacen_id);
        }else{
            $almacen = false;
        }
        
        if($almacen){
            $area->almacen()->associate($almacen);
        }else{
            $area->id_almacen = null;
        }

        $area->fill($request->all());
        
        if($area->tipo != $tipo && $area->tipo){
            $materiales_requeridos_tipo = $area->tipo->materialesRequeridos;
            foreach($materiales_requeridos_tipo as $material_requerido_tipo){
                $material_requerido = MaterialRequeridoArea::whereRaw("id_area = ". $area->id ." and id_material_requerido = ". $material_requerido_tipo->id)->first();
//meter despues lo de la validación de artículos asignados
                if($material_requerido != null){
                    if($material_requerido->cantidadMaterialesAsignados()>0){
                        $material_requerido->desvinculaMaterialRequeridoAreaTipo();
                    }else{
                        $material_requerido->delete();
                    }
                }
                
            }
        }

        $area->asignaTipo($tipo);
        
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
        
        if($tipo){
            $materiales_requeridos = [];
            $materiales_requeridos_candidatos = $area->getArticuloRequeridoDesdeAreaTipo($tipo);
            foreach($materiales_requeridos_candidatos as $material_requerido_candidato){
                $material_requerido = $area->materialesRequeridos->where("id_material", $material_requerido_candidato->id_material)->first();
                if($material_requerido != null){
                    if($material_requerido->cantidad_requerida == $material_requerido_candidato->cantidad_requerida){
                        $material_requerido->id_material_requerido = $material_requerido_candidato->id_material_requerido;
                        $material_requerido->save();
                    }
                }else{
                    $materiales_requeridos[] = $material_requerido_candidato;
                }
            }
            $area->materialesRequeridos()->saveMany($materiales_requeridos);
        }

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
