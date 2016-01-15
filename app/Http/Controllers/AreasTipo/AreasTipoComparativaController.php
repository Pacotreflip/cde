<?php

namespace Ghi\Http\Controllers\AreasTipo;

use Ghi\Equipamiento\Moneda;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreasTipo;
use Illuminate\Http\Request;
class AreasTipoComparativaController extends Controller
{
    /**
     * @var AreasTipo
     */
    private $tipos_area;

    /**
     * ComparativaTipoAreaController constructor.
     *
     * @param AreasTipo $tipos_area
     */
    public function __construct(AreasTipo $tipos_area)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->tipos_area = $tipos_area;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $filtros_consulta["casos"] = [];
        $filtros_consulta["errores"] = [];
        $filtros_consulta["grados_variacion"] = [];
        $tipo = $this->tipos_area->getById($id);
        $tipo_cambio = Moneda::where('nombre', 'DOLARES')->first()->tipoCambioMasReciente();
        $tipo_cambio_euro = Moneda::where('nombre', 'EUROS')->first()->tipoCambioMasReciente();
        $moneda_pesos = Moneda::where('nombre', 'PESOS')->first();
        $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        $monedas = Moneda::all();
        $tipos_cambio[0] = 0;
        $tipos_cambio[$moneda_pesos->id_moneda] = 1;
        $tipos_cambio[$tipo_cambio->id_moneda] = $tipo_cambio->cambio;
        $tipos_cambio[$tipo_cambio_euro->id_moneda] = $tipo_cambio_euro->cambio;
        //$informacion_articulos_esperados  = AreasTipo::getArticulosEsperados($id, $moneda_comparativa->id_moneda, $tipos_cambio, $filtros_consulta);
        //$articulos_esperados = $informacion_articulos_esperados["articulos_esperados"];
        $filtros = AreasTipo::getFiltros();
        return view('areas-tipo.comparativa', ["i"=>1
            , "articulos_esperados"=>null
            , "tipo_cambio_euro"=>number_format($tipo_cambio_euro->cambio,4)
            , "tipo_cambio_dolar"=>number_format($tipo_cambio->cambio,4)
            , "moneda_comparativa"=>$moneda_comparativa
            , "monedas"=>$monedas
            , "filtros"=>$filtros
            , "filtros_consulta"=> $filtros_consulta
            , "mostrar_personalizar" => 1
            , "resumen"=>null
            ])
            ->withTipo($tipo)
            ->withTipoCambio($tipo_cambio->cambio)
            ->withImporteTotal(0)
            ->withImporteTotalComparativa(0);
    }
    
    public function consultaFiltrada(Request $request, $id)
    {
        $filtros_consulta["casos"] = (is_array($request->casos))?$request->casos:[];
        $filtros_consulta["errores"] = (is_array($request->errores))?$request->errores:[];
        $filtros_consulta["grados_variacion"] = (is_array($request->grados_variacion))?$request->grados_variacion:[];
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

    public function comparativa($id)
    {
        $tipo = $this->tipos_area->getById($id);
        $moneda_homologada = Moneda::where('nombre', 'DOLARES')->first();
        $tipo_cambio = $moneda_homologada->tipoCambioMasReciente();

        $articulos = $tipo->materialesRequeridos->map(function ($material, $key) use ($tipo_cambio) {
            return [
                'id_material' => $material->id_material,
                'material' => $material->material->descripcion,
                'cantidad_requerida' => $material->cantidad_requerida,
                'precio_estimado' => $material->precio_estimado,
                'precio_estimado_homologado' => $material->getPrecioEstimado($tipo_cambio->cambio),
                'id_moneda' => $material->id_moneda,
                'moneda' => $material->moneda ? $material->moneda->nombre : '',
                'importe_estimado_homologado' => $material->getImporteEstimado($tipo_cambio->cambio),
                'cantidad_comparativa' => $material->cantidad_comparativa,
                'precio_comparativa' => $material->precio_comparativa,
                'precio_comparativa_homologado' => $material->getPrecioComparativa($tipo_cambio->cambio),
                'id_moneda_comparativa' => $material->id_moneda_comparativa,
                'moneda_comparativa' => $material->monedaComparativa ? $material->monedaComparativa->nombre : '',
                'importe_comparativa_homologado' => $material->getImporteComparativa($tipo_cambio->cambio),
                'existe_para_comparativa' => $material->existe_para_comparativa,
                'diferencia_costo_homologado' => abs($material->getImporteEstimado($tipo_cambio->cambio) - $material->getImporteComparativa($tipo_cambio->cambio)),
                'url' => route('articulos.edit', $material->id_material),
            ];
        });

        return response()->json([
            'tipo_cambio' => $tipo_cambio->cambio,
            'articulos' => $articulos,
        ]);
    }
}
