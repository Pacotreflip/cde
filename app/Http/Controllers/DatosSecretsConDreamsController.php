<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\ReporteCostos\DatosSecretsConDreams;
use Ghi\Equipamiento\Articulos\Materiales;
use Ghi\Equipamiento\ReporteCostos\AreaSecrets;
use Ghi\Equipamiento\ReporteCostos\AreaDreams;
use Ghi\Equipamiento\ReporteCostos\AreaReporte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class DatosSecretsConDreamsController extends Controller
{
    protected $materiales;
    
    public function __construct(Materiales $materiales) {
        $this->middleware('auth');
        $this->middleware('context');
        
        $this->materiales = $materiales;
        parent::__construct();
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $datos = $this->buscar($request->buscar);
        return view('datosSecretsConDreams.index')
                ->with('datosSecretsConDreams', $datos);
    }
    
    protected function buscar($busqueda, $howMany = 100)
    {
        return DatosSecretsConDreams::where(function ($query) use ($busqueda) {
            $query->where('no', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('proveedor', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('no_oc', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('descripcion_producto_oc', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_familia', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('familia', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_area_secrets', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_secrets', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_area_reporte', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_reporte', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_tipo', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('tipo', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_moneda_original', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('moneda_original', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('cantidad_comprada', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('recibidos_por_factura', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('unidad', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('precio', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('moneda', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('importe_sin_iva', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('fecha_factura', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('factura', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('fecha_pago', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_amr', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('fecha_entrega', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('pesos', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('dolares', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('euros', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('consolidado_dolares', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_material_secrets', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('proveedor_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('no_oc_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('descripcion_producto_oc_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_familia_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('familia_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_area_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_area_reporte_p_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_reporte_p_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_tipo_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('tipo_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('cantidad_comprada_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('cantidad_recibida_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('unidad_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('precio_unitario_antes_descuento_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('descuento_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('precio_unitario_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_moneda_original_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('moneda_original_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('importe_sin_iva_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('fecha_factura_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('factura_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('pagado_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('area_amr_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('fecha_entrega_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('presupuesto', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('pesos_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('dolares_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('euros_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('consolidacion_dolares_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('costo_x_habitacion_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('consolidado_banco_dreams', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('id_clasificacion', 'LIKE', '%'.$busqueda.'%')
                ->orWhere('clasificacion', 'LIKE', '%'.$busqueda.'%');
        })
            ->orderBy('id', 'DESC')
            ->paginate($howMany);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $familias = $this->materiales->getListaFamilias(\Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES);
        $areas_secrets = AreaSecrets::all()->lists('area_secrets', 'id');
        $areas_dreams = AreaDreams::all()->lists('area_dreams', 'id');
        $areas_reporte = AreaReporte::all()->lists('area_reporte', 'id');
        $tipos = DB::connection("cadeco")
                ->table("Equipamiento.material_clasificadores")
                ->select("id", "nombre")
                ->lists("nombre", "id");
        $monedas = DB::connection("cadeco")
                ->table("dbo.monedas")
                ->select("id_moneda", "nombre")
                ->lists("nombre", "id_moneda");
        
        return view ('datosSecretsConDreams.create')
                ->with('familias', $familias)
                ->with('areas_secrets', $areas_secrets)
                ->with('areas_dreams', $areas_dreams)
                ->with('areas_reporte', $areas_reporte)
                ->with('tipos', $tipos)
                ->with('monedas', $monedas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DatosSecretsConDreams::create(Input::all());
        Flash::success('Datos Agregados Correctamente');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('datosSecretsConDreams.show')
        ->with('dato', DatosSecretsConDreams::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $familias = $this->materiales->getListaFamilias(\Ghi\Equipamiento\Articulos\TipoMaterial::TIPO_MATERIALES);
        $areas_secrets = AreaSecrets::all()->lists('area_secrets', 'id');
        $areas_dreams = AreaDreams::all()->lists('area_dreams', 'id');
        $areas_reporte = AreaReporte::all()->lists('area_reporte', 'id');
        $tipos = DB::connection("cadeco")
                ->table("Equipamiento.material_clasificadores")
                ->select("id", "nombre")
                ->lists("nombre", "id");
        $monedas = DB::connection("cadeco")
                ->table("dbo.monedas")
                ->select("id_moneda", "nombre")
                ->lists("nombre", "id_moneda");
        $dato = DatosSecretsConDreams::find($id);
        
        return view('datosSecretsConDreams.edit')
                ->with('dato', $dato)
                ->with('familias', $familias)
                ->with('areas_secrets', $areas_secrets)
                ->with('areas_dreams', $areas_dreams)
                ->with('areas_reporte', $areas_reporte)
                ->with('tipos', $tipos)
                ->with('monedas', $monedas);
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
        $inputs = Input::all();
        unset($inputs['_token']);
        unset($inputs['_method']);

        DatosSecretsConDreams::where('id', $id)
        ->update($inputs);
        
        Flash::success('Datos Actualizados Correctamente');
        return redirect('datosSecretsConDreams');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dato = DatosSecretsConDreams::find($id);
        $dato->delete();
        
        Flash::success('Datos Eliminados Correctamente');
        return redirect('datosSecretsConDreams');
    }
}
