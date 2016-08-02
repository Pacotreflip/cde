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
    public function detalleDreams(){
        $datos_dreams  = Reporte::getMaterialesDreams();
        return view('reportes.presupuesto_detalle_dreams', [
            "datos_dreams"=>$datos_dreams,
            "i"=>1
        ]);
    }
}
