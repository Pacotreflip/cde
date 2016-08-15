<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Transacciones\PagoProgramado;
use Carbon\Carbon;
use Ghi\Http\Requests\CreatePagoProgramadoRequest;
use Ghi\Http\Requests\UpdatePagoProgramadoRequest;

class PagosProgramadosController extends Controller
{
    public function __construct() {
        
        $this->middleware('auth');
        $this->middleware('context');
        
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_transaccion)
    {
        $transaccion = Transaccion::findOrFail($id_transaccion);
        
        return view('pagos_programados.index')
                ->withCompra($transaccion);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_compra)
    {
        $compra = Transaccion::findOrFail($id_compra);
        $faltante = $compra->monto - $compra->totalProgramado();
        if (round($faltante, 2) == 0) {
            return response()->json(['error' => 'error']);
        }
        return view('pagos_programados.create')
                ->withCompra($compra);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePagoProgramadoRequest $request , $id_compra)
    {
        $compra = Transaccion::findOrFail($id_compra);
        
        $pagoProgramado = PagoProgramado::where([
            'fecha' => Carbon::parse($request->fecha)->toDateString(),
            'id_transaccion' => $compra->id_transaccion
        ])->first();
        
        if($pagoProgramado) {
            $pagoProgramado->monto =  $pagoProgramado->monto +$request->monto;
            $pagoProgramado->id_usuario = auth()->user()->idusuario;
            $pagoProgramado->observaciones = $request->observaciones;

            $pagoProgramado->save();
        }
        else {
            PagoProgramado::create([
                'fecha' => Carbon::parse($request->fecha)->toDateString(),
                'id_transaccion' => $compra->id_transaccion,
                'monto' => $request->monto,
                'observaciones' => $request->observaciones,
                'id_usuario' => auth()->user()->idusuario
            ]);
        }
        
       return response()->json(['Mensaje' => 'Registro exitoso de fecha de pago']);
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
    public function edit($id_compra, $id_pago)
    {
        $pago = PagoProgramado::findOrFail($id_pago);
        
        return view('pagos_programados.edit')
            ->withCompra(Transaccion::findOrFail($id_compra))
            ->withPago($pago);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePagoProgramadoRequest $request, $id_compra, $id_pago)
    {
        $pago = PagoProgramado::findOrFail($id_pago);
        
        $pago->observaciones = $request->observaciones;
        $pago->monto = $request->monto;
        $pago->fecha = Carbon::parse($request->fecha)->toDateString();
        $pago->id_usuario = auth()->user()->idusuario;
        $pago->save();
        
        return response()->json(['Mensaje' => 'Fecha de pago actualizada correctamente']);        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_compra, $id_pago)
    {
        $compra = Transaccion::findOrFail($id_compra);
        
        PagoProgramado::destroy($id_pago);
        
        return response()->json([
            'Mensaje' => 'Pago eliminado',
            'monto' => round($compra->monto, 2),
            'totalProgramado' => round($compra->totalProgramado(), 2),
            'faltante' => round(($compra->monto - $compra->totalProgramado()), 2)
        ]);
    }
}
