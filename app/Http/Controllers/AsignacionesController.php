<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Http\Requests\CreateAsignacionRequest;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Asignaciones\AsignaArticulos;
use Illuminate\Support\Facades\DB;

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
    
    protected function buscarArticulos($busqueda, $howMany = 15)
    {
        return \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                ->where('cantidad_existencia', '>', 0)
                ->where('id_obra', $this->getIdObra())
                ->whereHas('material', function ($query) use ($busqueda) { 
                    $query->where('descripcion', 'LIKE', '%'.$busqueda.'%')
                            ->orWhere('unidad','LIKE','%'.$busqueda.'%')
                            ->orWhere('numero_parte','LIKE','%'.$busqueda.'%');
                })
                ->whereHas('area', function($query) use ($busqueda) {
                    $query->where('nombre', 'LIKE', '%'.$busqueda.'%');                            
                })
                ->orderBy('id_area', 'ASC')
                ->paginate($howMany);     
    }
    
    protected function buscarArticulosArea($busqueda, $id, $howMany = 15)
    {
        return \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                
                ->where('cantidad_existencia', '>', 0)
                ->where('id_obra', $this->getIdObra())
                ->where('id_area', '=', $id)
                ->whereHas('material', function ($query) use ($busqueda) { 
                    $query->where('descripcion', 'LIKE', '%'.$busqueda.'%')
                            ->orWhere('unidad','LIKE','%'.$busqueda.'%')
                            ->orWhere('numero_parte','LIKE','%'.$busqueda.'%');
                })
                ->orderBy('id_area', 'ASC')
                ->paginate($howMany);         
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function showItems(Request $request, $id = null, $howmany = 15)
    {   
//        $articulos = \Ghi\Equipamiento\Inventarios\Inventario::with('material')->where('id_area', '=', 53)->where('cantidad_existencia','>',0)->get();
//        dd($articulos);
        $areasRaiz = $this->areas->getNivelesRaiz();
        
        if($id) {
            $articulos = $this->buscarArticulosArea($request->buscar, $id);
//            $articulos = \Ghi\Equipamiento\Inventarios\Inventario::with('material')
//                    ->where('id_area', '=', $id)
//                    ->where('cantidad_existencia','>',0)
//                    ->orderBy('id', 'ASC')
//                    ->paginate($howmany);
            
            return view('asignaciones.showItems')
                    ->withAreasraiz($areasRaiz)
                    ->withArticulos($articulos)
                    ->withCurrarea($this->areas->getById($id));
        } else {
            $articulos = $this->buscarArticulos($request->buscar);
//            $areas = \Ghi\Equipamiento\Areas\Area::
            return view('asignaciones.showItems')
                    ->withAreasraiz($areasRaiz)
                    ->withArticulos($articulos);
        }
    }
    
    protected function create($area, $id, $howMany = 15)
    {
        $articulo = \Ghi\Equipamiento\Inventarios\Inventario::with('area')
                ->with('material')
                ->where('id_area', '=', $area)
                ->where('id_material', '=', $id)
                ->first();
        
        $destinos = \Ghi\Equipamiento\Areas\Area::with('materialesRequeridos')
                ->whereHas('materialesRequeridos', function($query) use ($id) {
                    $query->where('id_material', '=', $id);               
                })
                ->paginate($howMany);                 
        return view('asignaciones.create')
            ->withArticulo($articulo)
            ->withDestinos($destinos);
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
    public function destroy($id)
    {
        //
    }
}
