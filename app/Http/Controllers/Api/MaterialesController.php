<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Material;

class MaterialesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('buscar')) {
            $busqueda = $request->buscar;

            return Material::soloMateriales()
                ->select('id_material', 'descripcion', 'numero_parte')
                ->where(function ($query) use ($busqueda) {
                    $query->where('descripcion', 'LIKE', '%'.$busqueda.'%')
                        ->orWhere('numero_parte', 'LIKE', '%'.$busqueda.'%')
                        ->orWhere('codigo_externo', 'LIKE', '%'.$busqueda.'%');
                })
                ->get();
        }

        return Material::soloMateriales()
            ->select('id_material', 'descripcion', 'numero_parte')
            ->get();
    }
    
    public function materialesOc(Request $request) {
        if(null !== ($request->input('depdrop_parents'))) {
            $parents = $request->input('depdrop_parents');
            
            if($parents != null) {
                $proveedor_id = $parents[0];
                $data = Material::join("items", "materiales.id_material","=", "items.id_material")
                    ->join("transacciones", "transacciones.id_transaccion","=", "items.id_transaccion")
                    ->join("empresas", "transacciones.id_empresa", "=", "empresas.id_empresa")
                    ->where("empresas.id_empresa", "=", $proveedor_id)
                    ->where("transacciones.tipo_transaccion", "=", "19")
                    ->groupBy('materiales.id_material', 'materiales.descripcion', 'materiales.numero_parte')
                    ->orderBy('materiales.descripcion')
                    ->select('materiales.id_material', 'materiales.descripcion', 'materiales.numero_parte')
                    ->get();
                $out = [];
                foreach($data as $d) {
                    $out[] = [
                        'id' => $d->id_material,
                        'name' => isset($d->numero_parte) ? '['.$d->numero_parte.'] '.$d->descripcion : $d->descripcion
                    ];
                }
                return response()->json(['output'=>$out, 'selected'=>'']);
            }            
        }
        return response()->json(['output'=>'', 'selected'=>'']);
    }
}
