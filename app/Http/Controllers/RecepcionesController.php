<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\Areas;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAlmacen;
use Ghi\Equipamiento\Recepciones\RecibeArticulosAsignacion;
use Ghi\Equipamiento\Recepciones\Recepciones;
use Illuminate\Support\Facades\DB;

class RecepcionesController extends Controller
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
        $recepciones = $this->buscar($request->buscar);

        return view('recepciones.index')
            ->withRecepciones($recepciones);
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
        return Recepcion::where('id_obra', $this->getIdObra())
        ->where(function ($query) use ($busqueda) {
            $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('persona_recibio', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                ->orWhereHas('empresa', function ($query) use ($busqueda) {
                    $query->where('razon_social', 'LIKE', '%'.$busqueda.'%');
                })
                ->orWhereHas('compra', function ($query) use($busqueda) {
                    $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%');
                });
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
    public function create(Areas $areas, $id_oc= 0)
    {
        $proveedores = Proveedor::join("transacciones", "empresas.id_empresa", "=", "transacciones.id_empresa")
                ->where("id_obra", "=", $this->getIdObra())
                ->whereRaw("equipamiento = 1 and transacciones.tipo_transaccion = 19")
                ->select(DB::raw("empresas.razon_social, empresas.id_empresa"))
                ->groupBy("empresas.id_empresa", "empresas.razon_social")
                ->orderBy("empresas.razon_social")
                ->get();

        $compras = Transaccion::ordenesCompraMateriales()
            ->where('id_obra', $this->getIdObra())
            ->orderBy('numero_folio', 'DESC')
            ->lists('numero_folio', 'id_transaccion')
            ->all();

        $areas = $areas->getListaAreas();

        return view('recepciones.create')
            ->with("id_oc", $id_oc)
            ->withProveedores($proveedores)
            ->withCompras($compras)
            ->withAreas($areas);
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
    
    public function destroy(Request $request,$id){
        $datos = ["id"=>$id, "motivo"=>$request->motivo];
         (new Recepciones($datos, $this->getObraEnContexto()))->cancelar();
        
    }
}
