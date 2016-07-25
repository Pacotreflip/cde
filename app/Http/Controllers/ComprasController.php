<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Maatwebsite\Excel\Facades\Excel;
class ComprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Muestra un listado de ordenes de compra.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $compras = $this->buscar($request->get('buscar'));

        return view('compras.index')
            ->withCompras($compras);
    }
    public function index_x_material($id_material)
    {
        $compras = $this->buscar_x_material($id_material);

        return view('compras.index')
            ->withCompras($compras);
    }
    

    /**
     * Busca ordenes de compra
     * 
     * @param string  $busqueda El texto a buscar
     * @param integer $howMany  Cuantos resultados por pagina
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function buscar($busqueda, $howMany = 15)
    {
        $salida =  Transaccion::ordenesCompraMateriales()
            ->join('items','items.id_transaccion','=','transacciones.id_transaccion')
            ->join('materiales','items.id_material','=','materiales.id_material')
            ->where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('materiales.descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhereHas('empresa', function ($query) use ($busqueda) {
                        $query->where('razon_social', 'LIKE', '%'.$busqueda.'%');
                    });
            })
            ->groupBy(['transacciones.id_transaccion','transacciones.numero_folio'
                ,'transacciones.fecha','transacciones.id_empresa','transacciones.observaciones'])
            ->orderBy('numero_folio', 'DESC')
            ->select('transacciones.id_transaccion','numero_folio','fecha','id_empresa','observaciones')        
            ->paginate($howMany);
            //dd($salida);
            return $salida;
    }
    
    protected function buscar_x_material($id_material, $howMany = 15)
    {
        $salida =  Transaccion::ordenesCompraMateriales()
            ->join('items','items.id_transaccion','=','transacciones.id_transaccion')
            
            ->where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($id_material) {
                $query->where('id_material', '=', $id_material);
            })
            ->groupBy(['transacciones.id_transaccion','transacciones.numero_folio'
                ,'transacciones.fecha','transacciones.id_empresa','transacciones.observaciones'])
            ->orderBy('numero_folio', 'DESC')
            ->select('transacciones.id_transaccion','numero_folio','fecha','id_empresa','observaciones')        
            ->paginate($howMany);
            //dd($salida);
            return $salida;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proveedores = Proveedor::orderBy('razon_social')
            ->lists('razon_social', 'id_empresa');

        $materiales = Material::selectRaw('LEFT(descripcion, 50) as descripcion, id_material')
            ->soloMateriales()->orderBy('descripcion')
            ->lists('descripcion', 'id_material')
            ->all();

        $ordenes = [];

        return view('compras.create')
            ->withProveedores($proveedores)
            ->withOrdenes($ordenes)
            ->withMateriales($materiales);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compra = Transaccion::with('items.recepciones')->findOrFail($id);

        return view('compras.show')
            ->withCompra($compra);
    }
    public function comprasXLS(){
        Excel::create('Compras'.date("Ymd_his"), function($excel)  {

            $excel->sheet("Compras", function($sheet)  {
                $arreglo = Transaccion::arregloXLS($this->getIdObra());
                $sheet->fromArray($arreglo);
                $sheet->row(1, function($row){
                    $row->setBackground('#cccccc');
                });
                $sheet->freezeFirstRow();
                
                $sheet->setAutoFilter();
            });
            
        })->export('xlsx');
    }
    
    public function comparativaCompraXLS($id){
        $compra = Transaccion::with('items.recepciones')->findOrFail($id);
        Excel::create('AnalisisDesviacionCompra_'.$compra->numero_folio."_".date("Ymd_his"), function($excel) use($compra)  {

            $excel->sheet("Comparativa", function($sheet) use($compra)  {
                $resultados = $compra->getComparativaXLS();
                //$arreglo1 = $resultados->toArray();
                $arreglo = json_decode(json_encode($resultados), true); 
                $keys = array_keys($arreglo);
                $encabezado = array_keys($arreglo[$keys[0]]);
                $encabezado = ["Proveedor", "No. O.C.", "Descripcion del Producto", "Cantidad Comprada O.C.", "Unidad", "Precio", "Moneda", "Importe sin IVA", "Proveedor", "Descripción", "Cantidad Solicitada AMR",
                    "Unidad", "Precio", "Moneda", "Importe", "Precio Unitario Dólares", "Importe Dólares", "PTTO", "Diferencial", "Crecimiento Solicitado AMR", "Piezas por Presupuesto"];
                $sheet->appendRow(["SECRETS","","","","","","","", "DREAMS","","","","","","","","", "COSTO", "","CANTIDAD","", "COSTO CON CRECIMIENTO"]);
                $sheet->appendRow($encabezado);
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('I1:Q1');
                $sheet->mergeCells('R1:S1');
                $sheet->mergeCells('T1:U1');
                $sheet->mergeCells('V1:V2');
                $sheet->cell('A1', function($cell) {
                    $cell->setBackground('#40E5EA')->setBorder("thin");
                    $cell->setAlignment("center");
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('I1', function($cell) {
                    $cell->setBackground('#EA9D40');
                    $cell->setAlignment("center");
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('R1', function($cell) {
                    $cell->setBackground('#FF5733');
                    $cell->setAlignment("center");
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('T1', function($cell) {
                    $cell->setBackground('#F1DB69');
                    $cell->setAlignment("center");
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('V1', function($cell) {
                    $cell->setBackground('#A9F169');
                    $cell->setAlignment("center");
                    $cell->setFontWeight('bold');
                });
                
                $sheet->cell('A2:H2', function($cell) {
                    $cell->setBackground('#40E5EA');
                    $cell->setAlignment("center");
                });
                
                $sheet->cell('I2:Q2', function($cell) {
                    $cell->setBackground('#EA9D40');
                    $cell->setAlignment("center");
                });
                
                $sheet->cell('R2', function($cell) {
                    $cell->setBackground('#F1DB69');
                    $cell->setAlignment("center");
                });
                $sheet->cell('S2:T2', function($cell) {
                    $cell->setBackground('#FF5733');
                    $cell->setAlignment("center");
                });
                $sheet->cell('U2:V2', function($cell) {
                    $cell->setBackground('#A9F169');
                    $cell->setAlignment("center");
                });
                ///$sheet->fromArray($arreglo);
                $sheet->row(2, function($row){
                    //$row->setBackground('#cccccc');
                });
                //$sheet->freezeFirstRow();
                
                //$sheet->setAutoFilter();
                
                $i = 2;
                $importe_sin_iva = 0;
                $importe_dolares = 0;
                $presupuesto = 0;
                $diferencial = 0;
                $piezas_presupuesto = 0;
                $costo_con_crecimiento = 0;
                foreach($arreglo as $partida){
                    //dd($producto);
                    $sheet->appendRow($partida);
                    $importe_sin_iva += str_replace(",","",$partida["importe_sin_iva"]);
                    $importe_dolares += str_replace(",","",$partida["importe_dolares"]);
                    $presupuesto += str_replace(",","",$partida["presupuesto"]);
                    $diferencial += str_replace(",","",$partida["diferencial"]);
                    $piezas_presupuesto += str_replace(",","",$partida["piezas_por_presupuesto"]);
                    $costo_con_crecimiento += str_replace(",","",$partida["costo_con_crecimiento"]);
                    $i++;
                }
                
                
                $sheet->appendRow(["","","","","","","",$importe_sin_iva, "","","","","","","","",$importe_dolares, $presupuesto, $diferencial,"",$piezas_presupuesto, $costo_con_crecimiento]);
                $sheet->setBorder('A1:V'.($i+1), 'thin');
                $sheet->cell('A'.($i+1).':V'.($i+1), function($cell) {
                    $cell->setBackground('#40E5EA');
                    $cell->setAlignment("right");
                });
                
                if($diferencial>0){
                    $sheet->cell('S'.($i+1), function($cell) {
                        $cell->setBackground('#FF5733');
                    });
                }else{
                    $sheet->cell('S'.($i+1), function($cell) {
                        $cell->setBackground('#A9F169');
                    });
                }
                if($importe_sin_iva>0){
                $incremento = ((($importe_dolares - $importe_sin_iva)/$importe_sin_iva));
                }else{
                    $incremento = "";
                }
                $sheet->appendRow(["","","","","","","","", "","","","","","","","INCREMENTO:",$incremento, "", "","","", ""]);
                $sheet->setBorder('P'.($i+2).':Q'.($i+2), 'thin');
               if($incremento>0){
                    $sheet->cell('P'.($i+2).':Q'.($i+2), function($cell) {
                        $cell->setBackground('#FF5733');
                    });
                }else{
                    $sheet->cell('P'.($i+2).':Q'.($i+2), function($cell) {
                        $cell->setBackground('#A9F169');
                    });
                }
            });
            
        })->export('xlsx');
    }
}
