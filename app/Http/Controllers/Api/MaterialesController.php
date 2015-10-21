<?php

namespace Ghi\Http\Controllers\Api;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Http\Controllers\Api\ApiController;

class MaterialesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
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
}
