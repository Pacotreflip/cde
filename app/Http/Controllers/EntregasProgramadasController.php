<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Transacciones\Item;
use Ghi\Equipamiento\Transacciones\EntregaProgramada;
use Carbon\Carbon;
use Ghi\Http\Requests\CreateEntregaProgramadaRequest;
use Ghi\Http\Requests\UpdateEntregaProgramadaRequest;

class EntregasProgramadasController extends Controller
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
    public function index($id_item)
    {
        return view('entregas_programadas.index')
                ->withItem(Item::find($id_item));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_item)
    {
        $item = Item::find($id_item);
        $faltante = $item->cantidad - $item->totalProgramado();
        if ($faltante == 0) {
            return response()->json(['error' => 'error']);
        }
        
        $fecha_entrega = Carbon::now()->toDateString();
        return view('entregas_programadas.create')
                ->withItem(Item::find($id_item))
                ->withFecha_entrega($fecha_entrega);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEntregaProgramadaRequest $request, $id_item)
    {
        $item = Item::find($id_item);        
        EntregaProgramada::create([
            'id_item' => $item->id_item,
            'cantidad_programada' => $request->input('cantidad'),
            'fecha_entrega' => Carbon::parse($request->input('fecha_entrega'))->toDateString(),
            'observaciones' => $request->input('observaciones'),
            'id_usuario' => auth()->user()->idusuario
        ]);
       
       return response()->json(['Mensaje' => 'Registro exitoso de fecha de entrega']);
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
        $entrega_programada = EntregaProgramada::find($id);
        return view('entregas_programadas.edit')
            ->withItem(Item::find($entrega_programada->id_item))
            ->withEntregaprogramada($entrega_programada);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEntregaProgramadaRequest $request, $id)
    {
        $entrega_programada = EntregaProgramada::findOrFail($id);
        $entrega_programada->cantidad_programada = $request->input('cantidad');
        $entrega_programada->fecha_entrega = Carbon::parse($request->input('fecha_entrega'))->toDateString();
        $entrega_programada->observaciones = $request->input('observaciones');
        $entrega_programada->id_usuario = auth()->user()->idusuario;
        $entrega_programada->save();
        
        return response()->json(['Mensaje' => 'Fecha de entrega actualizada correctamente']);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entrega_programada = EntregaProgramada::find($id);
        $item = Item::find($entrega_programada->id_item);
        
        EntregaProgramada::destroy($id);
        
        return response()->json([
            'Mensaje' => 'Entrega eliminada',
            'cantidad' => $item->cantidad,
            'totalProgramado' => $item->totalProgramado(),
            'faltante' => $item->cantidad - $item->totalProgramado()
        ]);
    }
}
