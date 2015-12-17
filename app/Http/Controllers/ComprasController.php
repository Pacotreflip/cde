<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;

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
        return Transaccion::ordenesCompraMateriales()
            ->where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ->orWhereHas('empresa', function ($query) use ($busqueda) {
                        $query->where('razon_social', 'LIKE', '%'.$busqueda.'%');
                    });
            })
            ->orderBy('numero_folio', 'DESC')
            ->paginate($howMany);
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
}
