<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Comprobantes\Comprobante;
use Ghi\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transferencias\Transferencia;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Cierres\Cierre;
use Ghi\Equipamiento\Transacciones\Entrega;
use Ghi\Http\Requests\AgregaComprobanteRequest;
use Ghi\Equipamiento\Recepciones\AgregaComprobanteARecepcion;
use Ghi\Equipamiento\Transferencias\AgregaComprobanteATransferencia;
use Ghi\Equipamiento\Asignaciones\AgregaComprobanteAAsignacion;
use Ghi\Equipamiento\Cierres\AgregaComprobanteACierre;
use Ghi\Equipamiento\Entregas\AgregaComprobanteAEntrega;

class ComprobantesController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_recepcion(AgregaComprobanteRequest $request, $id)
    {
        $recepcion = Recepcion::findOrFail($id);

        $comprobante = (new AgregaComprobanteARecepcion($recepcion, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function store_transferencia(AgregaComprobanteRequest $request, $id)
    {
        $transferencia = Transferencia::findOrFail($id);

        $comprobante = (new AgregaComprobanteATransferencia($transferencia, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function store_asignacion(AgregaComprobanteRequest $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $comprobante = (new AgregaComprobanteAAsignacion($asignacion, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function store_cierre(AgregaComprobanteRequest $request, $id)
    {
        $cierre = Cierre::findOrFail($id);

        $comprobante = (new AgregaComprobanteACierre($cierre, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function store_entrega(AgregaComprobanteRequest $request, $id)
    {
        $entrega = Entrega::findOrFail($id);

        $comprobante = (new AgregaComprobanteAEntrega($entrega, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function destroy($id, $id_comprobante)
    {
        $comprobante = Comprobante::findOrFail($id_comprobante);
        $files = $comprobante->thumbnail_path == $comprobante->baseDir().'/pdf.png' ? [$comprobante->path] : [$comprobante->path, $comprobante->thumbnail_path];

        $comprobante->delete();

        File::delete($files);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
