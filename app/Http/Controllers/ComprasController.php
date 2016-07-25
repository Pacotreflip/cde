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
        Excel::create('ComparativaCompra'.date("Ymd_his"), function($excel)  {

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
}
