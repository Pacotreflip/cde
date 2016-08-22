<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\ReporteCostos\DatosSecretsConDreams;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class RelacionarPresupuestoController extends Controller
{
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
    public function create($id_compra)
    {
        return view('relacionar_presupuesto.create')
                ->with([
                    'compra' => Transaccion::findOrFail($id_compra),
                    'datos_secrets_vs_dreams' => DatosSecretsConDreams::all()
                ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_compra, $id_dato = null)
    {
        $compra = Transaccion::findOrFail($id_compra);

        if($request->isMethod('post')) {
            foreach(Input::except('_token') as $key => $data) {
                if(!$compra->datosSecretsDreams->contains($key)) {
                    $compra->datosSecretsDreams()->attach($key);
                }
            }
            Flash::success(count(Input::except('_token')).' Datos relacionados con éxito');
            return redirect()->back();
        }
        
        if($compra->datosSecretsDreams->contains($id_dato)) {
            $status = '1';
        } else {
            $compra->datosSecretsDreams()->attach($id_dato);
            $status = '0';
        }
        return response()->json($status);
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
    public function destroy($id_compra, $id_dato)
    {
        dd('Eliminar', $id_compra, $id_dato);
    }
}
