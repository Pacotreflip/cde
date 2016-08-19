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

class ReportesPresupuestoController extends Controller
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
    
    
    public function index(){
        $reporte_ffe  = Reporte::getFFE();
        $reporte_ose  = Reporte::getOSE();
        $reporte_null  = Reporte::getNULL();
        $reporte  = Reporte::getTotal();
        return view('reportes.presupuesto', [
            "reporte"=>$reporte,
            "reporte_ose"=>$reporte_ose,
            "reporte_null"=>$reporte_null,
            "reporte_ffe"=>$reporte_ffe,"i"=>1
        ]);
    }
    public function detalleDreams(Request $request){
        $id_tipo = $request->id_tipo;
        $id_familia = $request->id_familia;
        $id_area_reporte = $request->id_area_reporte;
        $datos_dreams  = Reporte::getMaterialesDreams($id_tipo, $id_familia, $id_area_reporte);
        return view('reportes.presupuesto_detalle_dreams', [
            "datos_dreams"=>$datos_dreams,
            "i"=>1
        ]);
    }
    public function detalleSecrets(Request $request){
        $id_tipo = $request->id_tipo;
        $id_familia = $request->id_familia;
        $id_area_reporte = $request->id_area_reporte;
        $datos_secrets  = Reporte::getMaterialesSecrets($id_tipo, $id_familia, $id_area_reporte);
        return view('reportes.presupuesto_detalle_secrets', [
            "datos_secrets"=>$datos_secrets,
            "i"=>1
        ]);
    }
    public function detalleSecretsDreams(Request $request){
        $id_tipo = $request->id_tipo;
        $id_familia = $request->id_familia;
        $id_area_reporte = $request->id_area_reporte;
        $datos_secrets_dreams  = Reporte::getMaterialesSecretsDreams($id_tipo, $id_familia, $id_area_reporte);
        return view('reportes.presupuesto_detalle_secrets_dreams', [
            "datos_secrets_dreams"=>$datos_secrets_dreams,
            "i"=>1
        ]);
    }
}
