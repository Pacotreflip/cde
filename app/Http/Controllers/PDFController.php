<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transferencias\Transferencia;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Cierres\Cierre;
use Illuminate\Support\Facades\DB;



class PDFController extends Controller
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
    public function recepciones($id)
    {
        $recepcion = Recepcion::findOrFail($id);
        return view('pdf.recepciones')->withRecepcion($recepcion);        
    }
    
    public function transferencias($id)
    {
        $transferencia = Transferencia::findOrFail($id);
        return view('pdf.transferencias')->withTransferencia($transferencia);        
    }
    
    public function compras($id)
    {
        $compra = Transaccion::with('items.recepciones')->findOrFail($id);
        return view('pdf.compras')->withCompra($compra);        
    }
    
    public function asignaciones($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        return view('pdf.asignaciones')->withAsignacion($asignacion);
    } 
    
    
    public function articulosCierre($id){
        return DB::connection("cadeco")->select("SELECT  Equipamiento.areas.nombre as area_nombre, Equipamiento.cierres_partidas.id_area, dbo.materiales.id_material, dbo.materiales.descripcion, dbo.materiales.unidad as unidad, SUM(Equipamiento.asignacion_items.cantidad_asignada) as cantidad_asignada, dbo.materiales.numero_parte
            FROM
            dbo.materiales 
            INNER JOIN 
            Equipamiento.asignacion_items 
            ON 
            dbo.materiales.id_material = Equipamiento.asignacion_items.id_material
            INNER JOIN 
            Equipamiento.asignacion_item_validacion 
            ON 
            Equipamiento.asignacion_items.id = Equipamiento.asignacion_item_validacion.id_item_asignacion
            INNER JOIN
            Equipamiento.cierres_partidas_asignaciones
            ON
            Equipamiento.asignacion_item_validacion.id = Equipamiento.cierres_partidas_asignaciones.id_asignacion_item_validacion
            INNER JOIN
            (Equipamiento.cierres_partidas INNER JOIN Equipamiento.areas ON Equipamiento.cierres_partidas.id_area = Equipamiento.areas.id)
            ON
            Equipamiento.cierres_partidas_asignaciones.id_cierre_partida = Equipamiento.cierres_partidas.id
            INNER JOIN
            Equipamiento.cierres
            ON
            Equipamiento.cierres_partidas.id_cierre = Equipamiento.cierres.id
            WHERE Equipamiento.cierres.id = $id GROUP BY Equipamiento.areas.nombre, Equipamiento.cierres_partidas.id_area, dbo.materiales.id_material, dbo.materiales.descripcion, dbo.materiales.unidad, dbo.materiales.numero_parte ORDER BY dbo.materiales.numero_parte DESC");
    }
    public function cierres($id)
    {
        $cierre = Cierre::findOrFail($id);
        $articulos = $this->articulosCierre($id);

        return view('pdf.cierres', ["cierre"=> $cierre, "articulos"=>$articulos]);
    }
}
        

