<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Reporte\Reporte;
use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Facades\File;
//use Vinelab\Http\Client as HttpClient;
//use GuzzleHttp\Client;
use Zjango\Laracurl\Facades\Laracurl;

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
        $materiales_oc_venn  = Reporte::getMaterialesOCVSREQVENN($this->getIdObra());
        //dd($materiales_oc_venn);
        $moneda_comparativa = Moneda::where('nombre', 'DOLARES')->first();
        return view('reportes.materiales_oc_vs_req', ["i"=>1
            , "moneda_comparativa"=>$moneda_comparativa
            , "materiales_oc"=>$materiales_oc
            , "venn"=>$materiales_oc_venn
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
    public function index_estatus_desarrollo(){
        
       
        
        
        #opcion Laraculr
        //$response = Laracurl::get('https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/lists?cards=open&card_fields=name&fields=name&key=067986551ec72f4bfa9df9eb4bb202c6');
        //dd(json_decode($response->body));
        
        $response_listas = Laracurl::get("https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/lists?fields=name,id&key=067986551ec72f4bfa9df9eb4bb202c6");
        $listas = json_decode($response_listas->body);
        
        $response_cards = Laracurl::get("https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/cards?fields=name,idList,closed&key=067986551ec72f4bfa9df9eb4bb202c6&filter=open&attachments=true");
        $tareas = json_decode($response_cards->body);
        $i = 0;
        foreach($listas as $lista){
            $salida[$i]["name"] = $lista->name;
            foreach($tareas as $tarea){
                if($tarea->idList == $lista->id && $tarea->closed === false){
                    if(key_exists(0, $tarea->attachments)){
                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>$tarea->attachments[0]->url];
                    }else{
                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>"#"];
                    }
                }
            }
            
            $i++;
        }
        
        
        
        #OPCION ILLUMINATE
        //$request = \Illuminate\Http\Request::create('https://trello.com/b/3msVE1ks.json', 'GET');
        //dd($request);
        
        #OPCION VINELAB 1
        //$client = new HttpClient();
//        $response = $client->get("https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/lists?cards=open&card_fields=name&fields=name&key=067986551ec72f4bfa9df9eb4bb202c6");
//        $resumen = $response->json();
//        $response_listas = $client->get("https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/lists?fields=name,id&key=067986551ec72f4bfa9df9eb4bb202c6");
//        $listas = $response_listas->json();
//        
//        $response_cards = $client->get("https://api.trello.com/1/boards/5747231c2509b0bd9465ef3d/cards?fields=name,idList,closed&key=067986551ec72f4bfa9df9eb4bb202c6&filter=open&attachments=true");
//        $tareas = $response_cards->json();
//        $i = 0;
//        foreach($listas as $lista){
//            $salida[$i]["name"] = $lista->name;
//            foreach($tareas as $tarea){
//                if($tarea->idList == $lista->id && $tarea->closed === false){
//                    if(key_exists(0, $tarea->attachments)){
//                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>$tarea->attachments[0]->url];
//                    }else{
//                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>"#"];
//                    }
//                }
//            }
//            
//            $i++;
//        }
//dd($resumen);
        //dd($response->json());
//        
//        
        #opcion Vinelab ERROR
//        $response =  HttpClient::get('https://trello.com/b/3msVE1ks.json');
//        dd($response);
        
        #opcion GuzzleHttp NO DA CUERPO
//        $client = new Client();
//        $response = $client->get('https://trello.com/b/3msVE1ks.json');
//        dd($response);
        
        
        #OPCION ARCHIVO
//        $contents = File::get("uploads/estatus_desarrollo.txt");
//        $resumen = (json_decode($contents));
        //dd($resumen->cards);
//        $listas = $resumen->lists;
//        $tareas = $resumen->cards;
//        $salida = [];
//        $i = 0;
//        foreach($listas as $lista){
//            $salida[$i]["nombre"] = $lista->name;
//            foreach($tareas as $tarea){
//                if($tarea->idList == $lista->id && $tarea->closed === false){
//                    if(key_exists(0, $tarea->attachments)){
//                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>$tarea->attachments[0]->url];
//                    }else{
//                        $salida[$i]["tareas"][] = ["name"=>$tarea->name, "atach"=>"#"];
//                    }
//                }
//            }
//            
//            $i++;
//        }
        //dd($salida);
        return view('reportes.estado_desarrollo', ["datos"=>$salida,
            "ancho"=>(100/count($salida))
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
