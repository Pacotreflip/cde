<?php

namespace Ghi\Http\Controllers;

use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\ReporteCostos\DatosSecretsConDreams;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;

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
        $datos = DatosSecretsConDreams::leftjoin(
                            "Equipamiento.reporte_b_compra_vs_presupuesto"
                            , "Equipamiento.reporte_b_compra_vs_presupuesto.id_reporte_b_datos_secrets"
                            ,"="
                            ,"Equipamiento.reporte_b_datos_secrets.id")
                ->leftjoin(
                            "transacciones"
                            , "transacciones.id_transaccion"
                            ,"="
                            ,"Equipamiento.reporte_b_compra_vs_presupuesto.id_transaccion")
                ->where("reporte_b_datos_secrets.consolidado_dolares",">","0")->select(DB::raw("reporte_b_datos_secrets.consolidado_dolares * 1.22 as presupuesto_c,Equipamiento.reporte_b_datos_secrets.*, "
                        . "'# '+dbo.zerofill(4,transacciones.numero_folio) as folio_oc_dreams, transacciones.id_transaccion"))
                ->get();
        //dd($datos);
        return view('relacionar_presupuesto.create')
                ->with([
                    'compra' => Transaccion::findOrFail($id_compra),
                    'datos_secrets_vs_dreams' => $datos
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
        //$accion = $request->accion;
        $id_secrets = $request->id_secrets;
        $compra->datosSecretsDreams()->detach();
        foreach($id_secrets as $key => $value) {
            if(!$compra->datosSecretsDreams->contains($value)) {
                $compra->datosSecretsDreams()->attach($value);
            }
        }
        Flash::success(' Datos agregados con éxito');
        
//        if($accion == "agregar_p"){
//            foreach($id_secrets as $key => $value) {
//                if(!$compra->datosSecretsDreams->contains($value)) {
//                    $compra->datosSecretsDreams()->attach($value);
//                }
//            }
//            Flash::success(' Datos agregados con éxito');
//        }elseif($accion == "quitar_p"){
//            foreach($id_secrets as $key => $value) {
//                if(!$compra->datosSecretsDreams->contains($value)) {
//                    $compra->datosSecretsDreams()->deattach($value);
//                }
//            }
//            Flash::success(' Datos eliminados con éxito');
//        }elseif($accion == "reemplazar_p"){
//            foreach($id_secrets as $key => $value) {
//                if(!$compra->datosSecretsDreams->contains($value)) {
//                    $compra->datosSecretsDreams()->deattach($value);
//                }
//            }
//            Flash::success(' Datos eliminados con éxito');
//        }
        
            return redirect()->back();
        //dd($accion,$id_secrets);

//        if($request->isMethod('post')) {
//            foreach(Input::except('_token') as $key => $data) {
//                if(!$compra->datosSecretsDreams->contains($key)) {
//                    $compra->datosSecretsDreams()->attach($key);
//                }
//            }
//            Flash::success(count(Input::except('_token')).' Datos relacionados con éxito');
//            return redirect()->back();
//        }
        
//        if($compra->datosSecretsDreams->contains($id_dato)) {
//            $status = '1';
//        } else {
//            $compra->datosSecretsDreams()->attach($id_dato);
//            $status = '0';
//        }
//        return response()->json($status);
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
