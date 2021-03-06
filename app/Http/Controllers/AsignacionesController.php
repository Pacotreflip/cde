<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\CreateAsignacionRequest;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Asignaciones\AsignaArticulos;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Asignaciones\Asignaciones;

class AsignacionesController extends Controller
{
    protected $areas;
    
    public function __construct(\Ghi\Equipamiento\Areas\Areas $areas)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('context');
        $this->areas = $areas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $asignaciones = $this->buscar($request->buscar);

        return view('asignaciones.index')
            ->withAsignaciones($asignaciones);
    }
    
    /**
     * Busca recepciones de articulos.
     * 
     * @param string  $busqueda
     * @param integer $howMany
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function buscar($busqueda, $howMany = 15)
    {
        return Asignacion::where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('creado_por', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ;
            })
            ->orderBy('numero_folio', 'DESC')
            ->paginate($howMany);
    }
    
    protected function buscarArticulos($howMany = 15)
    {
        return \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                ->where('cantidad_existencia', '>', 0)
                ->where('id_obra', $this->getIdObra())
                ->orderBy('id_area', 'ASC')
                ->paginate($howMany);     
    }
    
    protected function buscarArticulosArea($id, $howMany = 15)
    {
        return \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                
                ->where('cantidad_existencia', '>', 0)
                ->where('id_obra', $this->getIdObra())
                ->where('id_area', '=', $id)
                ->orderBy('id_area', 'ASC')
                ->paginate($howMany);         
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function create($id = null, $howmany = 15)
    {           
        $inventarios = \Ghi\Equipamiento\Inventarios\Inventario::where('cantidad_existencia', '>', 0)->select('id_area')->get()->toArray();
        $areas = \Ghi\Equipamiento\Areas\Area::whereIn('id',$inventarios)->get();
        if($id) {
            $articulos = $this->buscarArticulosArea($id);
            
            return view('asignaciones.create')

                    ->withAreas($areas)
                        
                    ->withArticulos($articulos)
                    ->withCurrarea($this->areas->getById($id));
        } else {
            $articulos = $this->buscarArticulos();
            return view('asignaciones.create')
                    ->withAreas($areas)
                    ->withArticulos($articulos);
        }
    }
    
    protected function requerida($id_area, $id_material) {
        return DB::connection('cadeco')
                    ->table('Equipamiento.materiales_requeridos_area')
                    ->where('id_material', $id_material)
                    ->where('id_area', $id_area)
                    ->sum('cantidad_requerida');
    }
    
    protected function asignada($id_area, $id_material) {
        return DB::connection('cadeco')
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $id_material)
                    ->where('id_area_destino', $id_area)
                    ->sum('cantidad_asignada');
    }
    
    protected function getDestinos($id_articulo)
    {        
        $destinos = \Ghi\Equipamiento\Areas\Area::with('materialesRequeridos')
                ->whereHas('materialesRequeridos', function($query) use ($id_articulo) {
                    $query->where('id_material', '=', $id_articulo);               
                })
                ->get()
                ;
   
        $areasDestino = [];

        foreach ($destinos as $destino) {
            if($this->requerida($destino->id, $id_articulo) - $this->asignada($destino->id, $id_articulo) > 0)
            {
                $areasDestino[] = [
                    'id'                    => $destino->id,
                    'id_obra'               => $destino->id_obra,
                    'text'                  => $destino->nombre,
                    'path'                  => $destino->ruta(),
                    'cantidad'              => $this->requerida($destino->id, $id_articulo) - $this->asignada($destino->id, $id_articulo)
                ];
            }
        }
        return response()->json($areasDestino);
    }
    
    protected function getDestino($id_articulo, $id_destino)
    {        
        
        $destino = \Ghi\Equipamiento\Areas\Area::with('materialesRequeridos')
                ->whereHas('materialesRequeridos', function($query) use ($id_articulo, $id_destino) {
                    $query->where('id_material', '=', $id_articulo)
                          ->where('id_area', '=', $id_destino);               
                })
                ->get()   
                ;
        $areaDestino [] = [
                'id'                    => $destino[0]->id,
                'id_obra'               => $destino[0]->id_obra,
                'text'                  => $destino[0]->nombre,
                'path'                  => $destino[0]->ruta(),
                'cantidad'              => $destino[0]->cantidad_requerida($id_articulo)
                ];
        return response()->json($areaDestino);
    }
    
    protected function getMaterial($id_area, $id_articulo)
    {       
        $inventario = \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                ->where('id_material', '=', $id_articulo)
                ->where('id_area', '=', $id_area)
                ->where('id_obra', $this->getIdObra())
                ->first();
        
        $material = [
            "id"            => $inventario->id_material,
            "id_inventario" => $inventario->id,
            "numero_parte"  => $inventario->material->numero_parte,
            "descripcion"   => $inventario->material->descripcion,
            "unidad"        => $inventario->material->unidad,
            "existencia"    => $inventario->cantidad_existencia,
            "asignados"     => $inventario->material->cantidad_asignada($inventario->id_area),
            "esperados"     => $inventario->material->cantidad_esperada($inventario->id_area),
            'destinos'      => []
        ];
     
        return response()->json($material);
    }
    
    public function getMateriales(Request $request) {
        $ids = \Ghi\Equipamiento\Inventarios\Inventario::where('cantidad_existencia', '>', 0)->select('id_area')->distinct()->get()->toArray();
        $arrayIds = [];
        foreach($ids as $id){
            array_push($arrayIds, $id['id_area']);
        }
        $materiales = DB::connection('cadeco')->table('equipamiento.inventarios')
                ->join('dbo.materiales', 'equipamiento.inventarios.id_material', '=', 'dbo.materiales.id_material')
                ->whereIn('equipamiento.inventarios.id_area', $arrayIds)
                ->where('equipamiento.inventarios.id_obra', $this->getIdObra())
                ->where('dbo.materiales.descripcion', 'LIKE', '%'.$request->input('q').'%')
                ->where('cantidad_existencia', '>', 0)
                ->select('dbo.materiales.descripcion')
                ->distinct('dbo.materiales.id_material')
                ->get();  
        $data = [];
        foreach($materiales as $material) {
            array_push($data, $material->descripcion);
        }
        return response()->json($data)
                ->setCallback($request->input('callback'));
    }
    
    public function filtrar(Request $request) {
        $busqueda = $request->input('b');
        $areas = DB::connection('cadeco')->select(
                "SELECT I.id_area
                FROM Equipamiento.inventarios AS I
                INNER JOIN Equipamiento.areas AS A ON I.id_area = A.id
                INNER JOIN dbo.materiales AS M ON I.id_material = M.id_material
                WHERE cantidad_existencia > 0 AND M.descripcion LIKE '%$busqueda%' GROUP BY I.id_area");
        
        $data = [];
        foreach($areas as $area) {
            $data[] = [
                'id_area' => $area->id_area,
                'ruta' => \Ghi\Equipamiento\Areas\Area::find($area->id_area)->ruta()
            ];
        }
        return response()->json($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAsignacionRequest $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(CreateAsignacionRequest $request)
    {
        $asignacion = (new AsignaArticulos($request->all(), $this->getObraEnContexto()))->save();
        if ($request->ajax()) {
            return response()->json(['path' => route('asignaciones.show', $asignacion)]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asignacion = Asignacion::findOrFail($id);

        return view('asignaciones.show')
            ->withAsignacion($asignacion);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){
        $datos = ["id"=>$id, "motivo"=>$request->motivo];
        (new Asignaciones($datos, $this->getObraEnContexto()))->cancelar();
    }
}
