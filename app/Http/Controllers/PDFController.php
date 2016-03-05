<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;
use Ghi\Equipamiento\Recepciones\Recepcion;
use Ghi\Equipamiento\Transferencias\Transferencia;


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
}
