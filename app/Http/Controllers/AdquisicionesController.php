<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;

class AdquisicionesController extends Controller
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
        $ordenes = $this->buscar($request->get('buscar'));

        return view('adquisiciones.index')
            ->withOrdenes($ordenes);
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

        return view('adquisiciones.create')
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
        $orden = Transaccion::with('items.entregas')->findOrFail($id);

        return view('adquisiciones.show')
            ->withOrden($orden);
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
}
