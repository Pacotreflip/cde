<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Entregas\Entrega;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAlmacen;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAsignacion;
use Ghi\Equipamiento\Entregas\Entregas;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Asignaciones\AsignacionItemsValidados;
use Illuminate\Support\Facades\Auth;
use Ghi\Http\Requests\CreateEntregaRequest;
class EntregasController extends Controller
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
        $entregas = $this->buscar($request->buscar);

        return view('entregas.index')
            ->withEntregas($entregas);
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
        return Entrega::where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('entrega', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('recibe', 'LIKE', '%'.$busqueda.'%')
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
        $articulos = [];
        $i = 0;
        if(count($request->id_area)>0){
            foreach($request->id_area as $id_area){
                $objArea = Area::findOrFail($id_area);
                $areas[] = $objArea;
                if($objArea->es_almacen == 1){
                    $articulos_areas = $objArea->materiales_almacenados;
                }else{
                    $articulos_areas = $objArea->materialesAsignados();
                }
                foreach($articulos_areas as $articulo_area){
                    $articulos[$i] = $articulo_area;
                    $i++;
                }
            }
        }
        $fecha_entrega =  ($request->fecha_entrega == "")?date('Y-m-d'):$request->fecha_entrega;
        $concepto =  $request->concepto;
        $recibe =  $request->recibe;
        $entrega = ($request->entrega == "")? Auth::user()->present()->nombreCompleto: $request->entrega;
        $observaciones =  $request->observaciones;
        $areas_unique = array_unique($areas);
        return view('entregas.create')
        ->with("id_areas", $request->id_area)
                ->with("fecha_entrega", $fecha_entrega)
                ->with("concepto", $concepto)
                ->with("observaciones", $observaciones)
                ->with("recibe", $recibe)
                ->with("entrega", $entrega)
                ->with("i",1)
                ->with("articulos", $articulos)
                ->with("i2", 1)->
                withAreas($areas_unique);
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateRecepcionRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(CreateEntregaRequest $request) {
        $entregas = new Entregas();
        $entregas->generarEntrega($request->all(), $this->getObraEnContexto());
        return response()->json(['path' => route('entregas.index')]);
    }
    
    public function destroy(Request $request,$id){
        $entregas = new Entregas();
        $datos = ["id"=>$id, "motivo"=>$request->motivo];
        ($entregas->cancelar($datos, $this->getObraEnContexto()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cierre = Entrega::findOrFail($id);
        return view('entregas.show')
            ->withEntrega($cierre);
    }
    
    public function getFormularioBusquedaAreas(Request $request){
        $ids_areas = $request->id_area;
        $fecha_entrega =  ($request->fecha_entrega == "")?date('Y-m-d'):$request->fecha_entrega;
        $concepto =  $request->concepto;
        $recibe =  $request->recibe;
        $entrega = ($request->entrega == "")? Auth::user()->present()->nombreCompleto: $request->entrega;
        $observaciones =  $request->observaciones;
        return view('entregas.modal_busqueda_areas')
                ->with("ids_areas", $ids_areas)
                ->with("fecha_entrega", $fecha_entrega)
                ->with("concepto", $concepto)
                ->with("observaciones", $observaciones)
                ->with("recibe", $recibe)
                ->with("entrega", $entrega);
    }
    public function getAreas(Request $request){
        $parametro = $request->busqueda_area;
        $entregas = new \Ghi\Equipamiento\Entregas\Entregas();
        $id_areas = $request->id_area;
        if($id_areas == null){
            $id_areas = [];
        }
        $lista = $entregas->buscar($parametro);
        $salida = [];
        $i = 0;
        foreach($lista as $area){
            if($area instanceof Area){
                $salida[$i]["id"] = $area->id;
                $salida[$i]["descripcion"] = $area->nombre;
                $salida[$i]["clave"] = $area->clave;
                $salida[$i]["descripcion_ruta"] = $area->getRutaAttribute() . $area->nombre;
                $salida[$i]["ruta"] = $area->getRutaAttribute();
                
                $salida[$i]["articulos_asignados"] = $area->cantidad_asignada();
                $salida[$i]["articulos_requeridos"] = $area->cantidad_requerida();
                $salida[$i]["articulos_validados"] = $area->cantidad_validada();
                if($area->cierre_partida){
                    $salida[$i]["cerrada"] = 1;
                    $salida[$i]["cierre"] = "# ".$area->cierre_partida->cierre->numero_folio;
                    $salida[$i]["fecha_cierre"] = $area->cierre_partida->cierre->fecha_cierre->format('d-m-Y H:m');
                    
                    if($area->cierre_partida->entrega_partida){
                        $salida[$i]["entregada"] = 1;
                        $salida[$i]["entrega"] = "# ".$area->cierre_partida->entrega_partida->entrega->numero_folio;
                        $salida[$i]["fecha_entrega"] = $area->cierre_partida->entrega_partida->entrega->fecha_entrega->format('d-m-Y H:m');
                    }else{
                        $salida[$i]["entregada"] = 0;
                        $salida[$i]["entrega"] = "";
                        $salida[$i]["fecha_entrega"] = "";
                    }
                    
                    
                }else{
                    $salida[$i]["cerrada"] = 0;
                    $salida[$i]["fecha_cierre"] = "";
                    $salida[$i]["entregada"] = 0;
                    $salida[$i]["entrega"] = "";
                    $salida[$i]["fecha_entrega"] = "";
                }
                
                if(in_array($area->id, $id_areas)){
                    $salida[$i]["checked"] = "1";
                }else{
                    $salida[$i]["checked"] = "0";
                }
                
            }
           
            $i++;
        } 
        return response()->json($salida);
    }
    
    public function getAreasSeleccionadas(Request $request){
        $areas = [];
        $articulos = [];
        $i = 0;
        if(count($request->id_area)>0){
            foreach($request->id_area as $id_area){
                $objArea = Area::findOrFail($id_area);
                $areas[] = $objArea;
                
                if($objArea->es_almacen == 1){
                    $articulos_area = $objArea->materiales_almacenados;
                }else{
                    $articulos_area = $objArea->materialesAsignados;
                }
                
                foreach($articulos_area as $articulo_area){
                    $articulos[$i] = $articulo_area->material;
                    $i++;
                }
            }
        }
        $articulos_unique = array_unique($articulos);
        $articulos_col = new \Illuminate\Support\Collection($articulos);
      
        $fecha_entrega =  ($request->fecha_entrega == "")?date('Y-m-d'):$request->fecha_entrega;
        $concepto =  $request->concepto;
        $observaciones =  $request->observaciones;
        $recibe =  $request->recibe;
        $entrega = ($request->entrega == "")? Auth::user()->present()->nombreCompleto: $request->entrega;
        $areas_unique = array_unique($areas);
        return view('entregas.create')
        ->with("id_areas", $request->id_area)
                ->with("fecha_entrega", $fecha_entrega)
                ->with("concepto", $concepto)
                ->with("observaciones", $observaciones)
                ->with("recibe", $recibe)
                ->with("entrega", $entrega)
                ->with("i",1)
                ->with("articulos", $articulos_unique)
                ->with("i2", 1)->
                withAreas($areas_unique);
    }
    
    public function getFormularioValidacionArea($id_area){
        $area = Area::findOrFail($id_area);
        $i = 1;
        return view('entregas.modal_valida_area')->withArea($area)
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
