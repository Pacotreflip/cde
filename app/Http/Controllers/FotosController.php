<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Http\Requests\AgregaFotoRequest;
use Ghi\Equipamiento\Articulos\AgregaFotoAMaterial;

class FotosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Agrega una foto a un articulo.
     *
     * @param  Request  $request
     * @return Response
     */
    public function agregaFoto(AgregaFotoRequest $request, $id)
    {
        $material = Material::findOrFail($id);

        $foto = (new AgregaFotoAMaterial($material, $request->file('foto')))->save();

        if ($request->ajax()) {
            return response()->json($foto->thumbnail_path);
        }
    }
}
