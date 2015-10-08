<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Areas\AreaRepository;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Recepciones\RecibeArticulos;
use Ghi\Equipamiento\Transacciones\Transaccion;

class RecepcionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recepciones = $this->buscar($request->buscar);

        return view('recepciones.index')
            ->withRecepciones($recepciones);
    }

    /**
     * Busca recepciones de articulos.
     * 
     * @param string  $busqueda
     * @param integer $howMany
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function buscar($busqueda, $howMany = 15)
    {
        return Recepcion::where('id_obra', $this->getIdObra())
            ->where(function ($query) use ($busqueda) {
                $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('persona_recibe', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('observaciones', 'LIKE', '%'.$busqueda.'%')
                    ->orWhereHas('empresa', function ($query) use ($busqueda) {
                        $query->where('razon_social', 'LIKE', '%'.$busqueda.'%');
                    })
                    ->orWhereHas('compra', function ($query) use($busqueda) {
                        $query->where('numero_folio', 'LIKE', '%'.$busqueda.'%');
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
    public function create(AreaRepository $areas)
    {
        $proveedores = Proveedor::soloProveedores()
            ->orderBy('razon_social')
            ->lists('razon_social', 'id_empresa')->all();

        $compras = Transaccion::ordenesCompraMateriales()
            ->where('id_obra', $this->getIdObra())
            ->orderBy('numero_folio', 'DESC')
            ->lists('numero_folio', 'id_transaccion')->all();

        $areas = $areas->getListaAreas();

        return view('recepciones.create')
            ->withProveedores($proveedores)
            ->withCompras($compras)
            ->withAreas($areas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateRecepcionRequest $request)
    {
        $recepcion = (new RecibeArticulos($request->all(), $this->getObraEnContexto()))->save();

        if ($request->ajax()) {
            return response()->json(['path' => route('recepciones.show', $recepcion)]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recepcion = Recepcion::findOrFail($id);

        return view('recepciones.show')
            ->withRecepcion($recepcion);
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
