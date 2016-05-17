<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Reporte\Reporte;
use Maatwebsite\Excel\Facades\Excel;

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
    
    public function index_reporte_materiales_oc_vs_materiales_req(Request $request){
        $materiales_oc  = Reporte::getMaterialesOCVSREQ($this->getIdObra());
        $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        return view('reportes.materiales_oc_vs_req', ["i"=>1
            , "moneda_comparativa"=>$moneda_comparativa
            , "materiales_oc"=>$materiales_oc
        ]);
    }
    
    public function index_reporte_materiales_oc(Request $request){
        $materiales_oc  = Reporte::getMaterialesOC($this->getIdObra());
        $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        return view('reportes.materiales_en_oc', ["i"=>1
            , "moneda_comparativa"=>$moneda_comparativa
            , "materiales_oc"=>$materiales_oc
        ]);
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
        
//        $informacion_articulos_esperados  = Reporte::getDatos($moneda_comparativa->id_moneda, $tipos_cambio, $filtros_consulta);
//        $articulos_esperados = $informacion_articulos_esperados["articulos_esperados"];
//        $resumen = $informacion_articulos_esperados["resumen"];
        
        return view('reportes.comparativa', ["i"=>1
            , "articulos_esperados"=>""
            , "tipo_cambio_euro"=>number_format($tipo_cambio_euro->cambio,4)
            , "tipo_cambio_dolar"=>number_format($tipo_cambio->cambio,4)
            , "moneda_comparativa"=>$moneda_comparativa
            , "monedas"=>$monedas
            , "filtros"=>$filtros
            , "filtros_consulta"=> $filtros_consulta
            , "mostrar_personalizar" => 1
            , "resumen"=>""
        ])
            ->withTipoCambio($tipo_cambio->cambio)
            ->withImporteTotal(0)
            ->withImporteTotalComparativa(0);
    }
    
    public function recargaResultado(Request $request){
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
       // dd($request,$articulos_esperados);
        return view('reportes.partials.tabla', ["i"=>1
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

    public function descargaExcel(Request $request){
        
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
        $tipos_cambio[0] = 0;
        $tipos_cambio[$moneda_pesos->id_moneda] = 1;
        $tipos_cambio[$tipo_cambio->id_moneda] = $tipo_cambio->cambio;
        $tipos_cambio[$tipo_cambio_euro->id_moneda] = $tipo_cambio_euro->cambio;
        
        
        $informacion_articulos_esperados  = Reporte::getDatosXLS($moneda_comparativa->id_moneda, $tipos_cambio, $filtros_consulta);
        
        Excel::create('ReporteComparativa'. date("d-m-Y h:i:s"), function($excel) use($informacion_articulos_esperados) {
            
            $excel->sheet("Reporte", function($sheet) use($informacion_articulos_esperados) {
//                $arreglo = Producto::arregloInventario($ubicacion->idubicacion);
                $sheet->fromArray($informacion_articulos_esperados);
                $sheet->row(1, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->freezeFirstRow();
//                $sheet->cells('I1:I'.$arreglo->count(), function($cells){
//                    $cells->setBackground('#cccccc');
//                });
                $sheet->setAutoFilter();
//                $sheet->getStyle('A2:B2')->getProtection()->setLocked(
//                        //PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
//                );
//                //$sheet->protectCells('A2:B2');
            });
            
        })->export('xlsx');
        
    }
    
    public function materialesOCDescargaExcel(Request $request){
        
        $materiales_oc  = Reporte::getMaterialesOCXLS($this->getIdObra());
        
        Excel::create('ReporteMaterialesOC'. date("d-m-Y h:i:s"), function($excel) use($materiales_oc) {
            
            $excel->sheet("Reporte", function($sheet) use($materiales_oc) {
//                $arreglo = Producto::arregloInventario($ubicacion->idubicacion);
                $sheet->fromArray($materiales_oc);
                $sheet->row(1, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->freezeFirstRow();
//                $sheet->cells('I1:I'.$arreglo->count(), function($cells){
//                    $cells->setBackground('#cccccc');
//                });
                $sheet->setAutoFilter();
//                $sheet->getStyle('A2:B2')->getProtection()->setLocked(
//                        //PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
//                );
//                //$sheet->protectCells('A2:B2');
                $sheet->setColumnFormat(array(
                   
                    'D' => '0.00',
                    
                ));
            });
            
        })->export('xlsx');
        
    }
    function materialesOCVSREQDescargaExcel(Request $request){
        $materiales_oc  = Reporte::getMaterialesOCVSREQXLS($this->getIdObra());
        
        Excel::create('ReporteMaterialesOCVsREQ'. date("d-m-Y h:i:s"), function($excel) use($materiales_oc) {
            
            $excel->sheet("Reporte", function($sheet) use($materiales_oc) {
//                $arreglo = Producto::arregloInventario($ubicacion->idubicacion);
                $sheet->fromArray($materiales_oc);
                $sheet->row(1, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->freezeFirstRow();
//                $sheet->cells('I1:I'.$arreglo->count(), function($cells){
//                    $cells->setBackground('#cccccc');
//                });
                $sheet->setAutoFilter();
//                $sheet->getStyle('A2:B2')->getProtection()->setLocked(
//                        //PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
//                );
//                //$sheet->protectCells('A2:B2');
                $sheet->setColumnFormat(array(
                   
                    'D' => '0.00',
                    
                ));
            });
            
        })->export('xlsx');
    }
}
