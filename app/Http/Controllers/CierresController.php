<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Cierres\Cierre;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAlmacen;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAsignacion;
use Ghi\Equipamiento\Cierres\Cierres;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Asignaciones\AsignacionItemsValidados;
use Illuminate\Support\Facades\Auth;
use Ghi\Http\Requests\CreateCierreRequest;
class CierresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Muestra un listado de recepciones generadas.
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cierres = $this->buscar($request->buscar);

        return view('cierres.index')
            ->withCierres($cierres);
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
        return Cierre::where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ;
            })
            ->orderBy('numero_folio', 'DESC')
            ->paginate($howMany);
    }

    /**
     * Muestra un formulario para crear una recepcion.
     *
     * @param Areas $areas
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $areas = [];
        if(count($request->id_area)>0){
            foreach($request->id_area as $id_area){
                $areas[] = Area::findOrFail($id_area);
            }
        }
        
        return view('cierres.create')->withAreas($areas);
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateRecepcionRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreateCierreRequest $request) {
        $cierres = new Cierres();
        $cierres->generarCierre($request->all(), $this->getObraEnContexto());
        return response()->json(['path' => route('cierres.index')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cierre = Cierre::findOrFail($id);
        return view('cierres.show')
            ->withCierre($cierre);
    }
    
    public function getFormularioBusquedaAreas(){
        return view('cierres.modal_busqueda_areas');
    }
    public function getAreas(Request $request){
        $parametro = $request->busqueda_area;
        $cierres = new \Ghi\Equipamiento\Cierres\Cierres();
        $lista = $cierres->buscar($parametro);
        $salida = [];
        $i = 0;
        foreach($lista as $area){
            if($area instanceof Area){
                $salida[$i]["id"] = $area->id;
                $salida[$i]["descripcion"] = $area->nombre;
                $salida[$i]["clave"] = $area->clave;
                $salida[$i]["descripcion_ruta"] = $area->getRutaAttribute() . $area->nombre;
                $salida[$i]["ruta"] = $area->getRutaAttribute();
                $salida[$i]["cerrable"] = $area->esCerrable();
                $salida[$i]["articulos_asignados"] = $area->cantidad_asignada();
                $salida[$i]["articulos_requeridos"] = $area->cantidad_requerida();
                $salida[$i]["articulos_validados"] = $area->cantidad_validada();
                if($area->cierre_partida){
                    $salida[$i]["cerrada"] = 1;
                    $salida[$i]["cierre"] = "# ".$area->cierre_partida->cierre->numero_folio;
                    $salida[$i]["fecha_cierre"] = $area->cierre_partida->cierre->fecha_cierre->format('d-m-Y H:m');
                }else{
                    $salida[$i]["cerrada"] = 0;
                    $salida[$i]["cierre"] = "";
                    $salida[$i]["fecha_cierre"] = "";
                }
                
            }
            $i++;
        }
        return response()->json($salida);
    }
    
    public function getAreasSeleccionadas(Request $request){
        $areas = [];
        if(count($request->id_area)>0){
            foreach($request->id_area as $id_area){
                $areas[] = Area::findOrFail($id_area);
            }
        }
        return view('cierres.create')->withAreas($areas);
    }
    
    public function getFormularioValidacionArea($id_area){
        $area = Area::findOrFail($id_area);
        $i = 1;
        return view('cierres.modal_valida_area')->withArea($area)
                ->withI($i);
    }
    public function validarAsignaciones(Request $request){
        $articulos_validados_anteriores = $request->idarticulo_requerido_validado;
        $articulos_validar = $request->idarticulo_requerido;
        if(!$articulos_validar){
            $articulos_validar = [];
        }
        if(!$articulos_validados_anteriores){
            $articulos_validados_anteriores = [];
        }
        foreach($articulos_validar as $idarticulo_requerido){
            $articulo_requerido = MaterialRequeridoArea::findOrFail($idarticulo_requerido);
            foreach($articulo_requerido->itemsAsignacion as $itemAsignacion){
                $asignacion_item_validado = AsignacionItemsValidados::whereRaw("id_item_asignacion =" . $itemAsignacion->id )->first();
                
                if(!$asignacion_item_validado){
                    AsignacionItemsValidados::create(["id_item_asignacion"=>$itemAsignacion->id, "id_usuario"=>Auth::id()]);
                }
            }
        }
        
        foreach($articulos_validados_anteriores as $idarticulo_validado_anterior){
            if(!in_array($idarticulo_validado_anterior, $articulos_validar)){
                $articulo_requerido_quitar = MaterialRequeridoArea::findOrFail($idarticulo_validado_anterior);
                foreach($articulo_requerido_quitar->itemsAsignacion as $itemAsignacion){
                    $asignacion_item_validado_anterior = AsignacionItemsValidados::whereRaw("id_item_asignacion =" . $itemAsignacion->id )->first();

                    if($asignacion_item_validado_anterior){
                        $asignacion_item_validado_anterior->delete();
                    }
                }
            }
        }
    }
    public function validarTodasAsignaciones(Request $request, $id_area){
        $area = Area::findOrFail($id_area);
        foreach($area->materialesRequeridos as $articulo_requerido){
            foreach($articulo_requerido->itemsAsignacion as $itemAsignacion){
                $asignacion_item_validado = AsignacionItemsValidados::whereRaw("id_item_asignacion =" . $itemAsignacion->id )->first();
                
                if(!$asignacion_item_validado){
                    AsignacionItemsValidados::create(["id_item_asignacion"=>$itemAsignacion->id, "id_usuario"=>Auth::id()]);
                }
            }
        }
    }
}
