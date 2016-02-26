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
use \Ghi\Equipamiento\Areas\Area;
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
    public function create(Areas $areas)
    {
        

        //$areas_arreglo = $areas->getListaAreasCerrables();
        //dd($areas);

        return view('cierres.create')
            ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\CreateRecepcionRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Requests\CreateRecepcionRequest $request) {
        if ($request->opcion_recepcion == "asignar") {
            $recepcion_asignacion = (new RecibeArticulosAsignacion($request->all(), $this->getObraEnContexto()))->save();
            if ($request->ajax()) {
                return response()->json(['path' => route('asignaciones.show', $recepcion_asignacion)]);
            }
        } elseif ($request->opcion_recepcion == "almacenar") {
            $recepcion = (new RecibeArticulosAlmacen($request->all(), $this->getObraEnContexto()))->save();
            if ($request->ajax()) {
                return response()->json(['path' => route('recepciones.show', $recepcion)]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recepcion = Recepcion::findOrFail($id);

        return view('recepciones.show')
            ->withRecepcion($recepcion);
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
            }
            $i++;
        }
        return response()->json($salida);
    }
}
