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
    public function index()
    {
        $ordenes = Transaccion::ordenesCompraMateriales()
            ->orderBy('numero_folio', 'DESC')
            ->paginate();

        return view('adquisiciones.index')
            ->withOrdenes($ordenes);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
