<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Recepciones\Comprobante;
use Ghi\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Http\Requests\AgregaComprobanteRequest;
use Ghi\Equipamiento\Recepciones\AgregaComprobanteARecepcion;

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
    public function store(AgregaComprobanteRequest $request, $id)
    {
        $recepcion = Recepcion::findOrFail($id);

        $comprobante = (new AgregaComprobanteARecepcion($recepcion, $request->file('comprobante')))->save();

        if ($request->ajax()) {
            return response()->json($comprobante->thumbnail_path);
        }
    }
    
    public function destroy($id_recepcion, $id)
    {
        $comprobante = Comprobante::findOrFail($id);
        $files = [$comprobante->path, $comprobante->thumbnail_path];

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
