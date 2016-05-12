<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Reporte\Reporte;
class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');
        parent::__construct();
    }
    public function index_reporte_comparativa(Request $request)
    {
        $filtros_consulta["casos"] = (is_array($request->casos))?$request->casos:[];
        $filtros_consulta["errores"] = (is_array($request->errores))?$request->errores:[];
        $filtros_consulta["grados_variacion"] = (is_array($request->grados_variacion))?$request->grados_variacion:[];
        $filtros_consulta["familias"] = (is_array($request->familias))?$request->familias:[];
        $filtros_consulta["clasificadores"] = (is_array($request->clasificadores))?$request->clasificadores:[];
        $filtros_consulta["descripcion"] = $request->descripcion;
        $filtros_consulta["areas_tipo"] = (is_array($request->areas_tipo))?$request->areas_tipo:[];
        $filtros_consulta["areas"] = (is_array($request->areas))?$request->areas:[];
        
        $tipo_cambio = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();
        $tipo_cambio_euro = Moneda::where('nombre', 'EUROS')->first()->tipoCambioMasReciente();
        $moneda_pesos = Moneda::where('nombre', 'PESOS')->first();
        $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        $monedas = Moneda::all();
        $tipos_cambio[0] = 0;
        $tipos_cambio[$moneda_pesos->id_moneda] = 1;
        $tipos_cambio[$tipo_cambio->id_moneda] = $tipo_cambio->cambio;
        $tipos_cambio[$tipo_cambio_euro->id_moneda] = $tipo_cambio_euro->cambio;
        
        $filtros = Reporte::getFiltros();
        
        $informacion_articulos_esperados  = Reporte::getDatos($moneda_comparativa->id_moneda, $tipos_cambio, $filtros_consulta);
        $articulos_esperados = $informacion_articulos_esperados["articulos_esperados"];
        $resumen = $informacion_articulos_esperados["resumen"];
        
        return view('reportes.comparativa', ["i"=>1
            , "articulos_esperados"=>$articulos_esperados
            , "tipo_cambio_euro"=>number_format($tipo_cambio_euro->cambio,4)
            , "tipo_cambio_dolar"=>number_format($tipo_cambio->cambio,4)
            , "moneda_comparativa"=>$moneda_comparativa
            , "monedas"=>$monedas
            , "filtros"=>$filtros
            , "filtros_consulta"=> $filtros_consulta
            , "mostrar_personalizar" => 1
            , "resumen"=>$resumen
        ])
            ->withTipoCambio($tipo_cambio->cambio)
            ->withImporteTotal(0)
            ->withImporteTotalComparativa(0);
    }
    
    public function consultaFiltrada(Request $request, $id)
    {
        $filtros_consulta["casos"] = (is_array($request->casos))?$request->casos:[];
        $filtros_consulta["errores"] = (is_array($request->errores))?$request->errores:[];
        $filtros_consulta["grados_variacion"] = (is_array($request->grados_variacion))?$request->grados_variacion:[];
        $filtros_consulta["familias"] = (is_array($request->familias))?$request->familias:[];
        $filtros_consulta["clasificadores"] = (is_array($request->clasificadores))?$request->clasificadores:[];
        $filtros_consulta["descripcion"] = $request->descripcion;
        //dd($filtros);
        
        $tipo = $this->tipos_area->getById($id);
        
        $moneda_pesos = Moneda::where('nombre', 'PESOS')->first();
        if($request->moneda_comparativa>0){
            $moneda_comparativa = Moneda::where('id_moneda', $request->moneda_comparativa)->first();
        }else{
            $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        }
        
        $tipo_cambio_dolar = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();
        $tipo_cambio_euro = Moneda::where('nombre', 'EUROS')->first()->tipoCambioMasReciente();
        
        
        $tipos_cambio[0] = 0;
        $tipos_cambio[$moneda_pesos->id_moneda] = 1;
        $tipos_cambio[$tipo_cambio_dolar->id_moneda] = ($request->tipo_cambio_dolar>0)? $request->tipo_cambio_dolar :$tipo_cambio_dolar->cambio;
        $tipos_cambio[$tipo_cambio_euro->id_moneda] = ($request->tipo_cambio_euro>0)? $request->tipo_cambio_euro :$tipo_cambio_euro->cambio;
        
        $informacion_articulos_esperados  = AreasTipo::getArticulosEsperados($id, $moneda_comparativa->id_moneda, $tipos_cambio, $filtros_consulta);
        $articulos_esperados = $informacion_articulos_esperados["articulos_esperados"];
        $resumen = $informacion_articulos_esperados["resumen"];
        $filtros = AreasTipo::getFiltros();
        $familias = $this->materiales->getListaFamilias(TipoMaterial::TIPO_MATERIALES);
        $clasificadores = $this->clasificadores->getAsList();
        $filtros["familias"] = $familias->toArray();
        $filtros["clasificadores"] = $clasificadores;
        $monedas = Moneda::all();
        return view('areas-tipo.comparativa', ["i"=>1,"articulos_esperados"=>$articulos_esperados
                , "tipo_cambio_euro"=>($request->tipo_cambio_euro>0)? number_format($request->tipo_cambio_euro,4) :number_format($tipo_cambio_euro->cambio,4)
                , "tipo_cambio_dolar"=>($request->tipo_cambio_dolar>0)? number_format($request->tipo_cambio_dolar,4) :number_format($tipo_cambio_dolar->cambio,4)
                , "moneda_comparativa"=>$moneda_comparativa
                , "monedas"=>$monedas
                , "filtros"=>$filtros
                , "filtros_consulta"=> $filtros_consulta
                , "mostrar_personalizar" => 0
                , "resumen"=>$resumen
                ])
            ->withTipo($tipo)
            ->withTipoCambio($tipo_cambio_dolar->cambio)
            ->withImporteTotal(0)
            ->withImporteTotalComparativa(0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
